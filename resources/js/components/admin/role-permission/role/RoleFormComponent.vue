<template>
  <form @submit.prevent="submitForm()">
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="name"
            >Role Name <span class="text-danger">*</span></label
          >
          <div>
            <input
              v-model="form_data.name"
              id="name"
              type="text"
              class="form-control"
              placeholder="Enter role name"
              :disabled="is_edit && !form_data.is_name_editable"
            />
            <required-component
              v-if="show_error && !$v.form_data.name.required"
            />
            <minlength-component
              v-if="show_error && !$v.form_data.name.minLength"
              :min_length="$v.form_data.name.$params.minLength.min"
            />
          </div>
        </div>
      </div>
      <!--end col-->
      <div class="col-lg-12">
        <h6 class="mt-0 header-title all-permission-heading d-flex">
          <span class="text-left">Check all permissions</span>
          <input
            type="checkbox"
            v-model.lazy="form_data.all_permission"
            name="all_permission"
            value="1"
            @change="allPermissionCheck()"
            class="all-permission"
          />
        </h6>
        <div id="accordion">
          <template v-for="(permission, parent_index) in permissions">
            <div class="accordion-item card mb-1" :key="parent_index">
              <div
                class="card-header p-3"
                :id="makeAsSlug(permission.name) + '-' + parent_index"
              >
                <h6 class="m-0 font-14 d-flex">
                  <a
                    :href="
                      '#' +
                      makeAsSlug(permission.name) +
                      '-collapse-' +
                      parent_index
                    "
                    class="text-dark"
                    data-bs-toggle="collapse"
                    aria-expanded="true"
                    :aria-controls="
                      makeAsSlug(permission.name) + '-collapse-' + parent_index
                    "
                  >
                    {{ permission.name }}
                  </a>
                  <input
                    type="checkbox"
                    name="parent_permission[]"
                    v-model="form_data.parent_permissions"
                    @change="parentPermissionChanged(permission.id)"
                    :id="'parent-permission-id-' + permission.id"
                    :data-child="'parent-permission-id-' + permission.id"
                    class="parent-permission"
                    :value="permission.name + '-all'"
                  />
                </h6>
              </div>

              <div
                :id="makeAsSlug(permission.name) + '-collapse-' + parent_index"
                class="collapse show"
                :aria-labelledby="
                  makeAsSlug(permission.name) + '-' + parent_index
                "
                data-bs-parent="#accordion"
              >
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      {{ permission.name }}
                      <input
                        type="checkbox"
                        name="permissions[]"
                        v-model="form_data.permissions"
                        :id="'child-permission-' + permission.id"
                        :data-bs-parent="
                          'parent-permission-id-' + permission.id
                        "
                        :class="
                          'child-permission parent-permission-id-' +
                          permission.id
                        "
                        @change="childPermissionChanged(permission.id)"
                        :value="permission.name"
                      />
                    </li>
                    <template
                      v-for="(child, child_index) in permission.children"
                    >
                      <li class="list-group-item" :key="child_index">
                        {{ ucFirst(child.name) }}
                        <input
                          type="checkbox"
                          name="permissions[]"
                          v-model="form_data.permissions"
                          :id="'child-permission-' + child.id"
                          :data-bs-parent="
                            'parent-permission-id-' + permission.id
                          "
                          :class="
                            'child-permission parent-permission-id-' +
                            permission.id
                          "
                          @change="childPermissionChanged(permission.id)"
                          :value="child.name"
                        />
                      </li>
                    </template>
                  </ul>
                </div>
              </div>
            </div>
          </template>
        </div>

        <required-component
          v-if="show_error && !$v.form_data.permissions.required"
          message="Please select permission."
        />
        <minlength-component
          v-if="show_error && !$v.form_data.permissions.minLength"
          :min_length="$v.form_data.permissions.$params.minLength.min"
        />
      </div>
    </div>
    <!--end row-->

    <div class="row mt-3">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-dark" :disabled="is_loading">
          <span v-if="is_loading">Loading...</span>
          <span v-else>{{ !is_edit ? "Create Role" : "Update Role" }}</span>
        </button>
        <a href="/roles" class="btn btn-danger" :disabled="is_loading">
          <span>Cancel</span>
        </a>
      </div>
      <!--end col-->
    </div>
    <!--end row-->
  </form>
  <!--end form-->
</template>

<script>
import { required, minLength } from "vuelidate/lib/validators";
import RequiredComponent from "@/components/shared/error/RequiredComponent";
import MinlengthComponent from "@/components/shared/error/MinlengthComponent";

export default {
  name: "RoleFormComponent",
  components: { RequiredComponent, MinlengthComponent },
  props: {
    is_edit: {
      type: Boolean,
      default: false,
    },
    model_data: {
      type: Object,
      default: {},
    },
    all_permissions: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      form_data: {
        name: null,
        permissions: [],
        parent_permissions: [],
        all_permission: false,
      },
      permissions: [],
      show_error: false,
      is_loading: false,
    };
  },
  validations: {
    form_data: {
      name: {
        required,
        minLength: minLength(3),
      },
      permissions: {
        required,
        minLength: minLength(1),
      },
    },
  },
  mounted() {
    this.permissions = this.all_permissions;

    if (this.is_edit) {
      this.form_data.name = this.model_data.name;
      this.form_data.permissions = this.model_data.permissions.map(
        (item) => item.name
      );
      this.checkAllPermissionCheckedOrNot();
      this.checkParentPermissionOrNot();
    }
  },
  methods: {
    makeAsSlug(name) {
      return name.replace(/ /g, "-");
    },
    allPermissionCheck() {
      let self = this;
      let allPermission = self.form_data.all_permission;
      if (allPermission) {
        self.form_data.parent_permissions = [];
        self.form_data.permissions = [];
        self.permissions.forEach((permission) => {
          self.form_data.parent_permissions.push(permission.name + "-all");
          self.form_data.permissions.push(permission.name);
          permission.children.forEach((child) => {
            self.form_data.permissions.push(child.name);
          });
        });
      } else {
        self.form_data.parent_permissions = [];
        self.form_data.permissions = [];
      }
    },
    parentPermissionChanged(permission_id) {
      let self = this;
      let permissions = self.permissions.find((permission) => {
        return permission.id == permission_id;
      });

      if (
        self.form_data.parent_permissions.includes(permissions.name + "-all")
      ) {
        if (!self.form_data.permissions.includes(permissions.name)) {
          self.form_data.permissions.push(permissions.name);
        }
        permissions.children.forEach((p) => {
          if (!self.form_data.permissions.includes(p.name)) {
            self.form_data.permissions.push(p.name);
          }
        });
      } else {
        if (self.form_data.permissions.includes(permissions.name)) {
          let pmainIndex = self.form_data.permissions.indexOf(permissions.name);
          if (pmainIndex !== -1) {
            self.form_data.permissions.splice(pmainIndex, 1);
          }
        }
        permissions.children.forEach((p) => {
          if (self.form_data.permissions.includes(p.name)) {
            let pIndex = self.form_data.permissions.indexOf(p.name);
            if (pIndex !== -1) {
              self.form_data.permissions.splice(pIndex, 1);
            }
          }
        });
      }
      self.checkParentPermissionCheckedOrNot();
    },
    childPermissionChanged(permission_id) {
      let self = this;
      let permissions = self.permissions.find(
        (permission) => permission.id == permission_id
      );
      let permissionLength = permissions.children.length + 1;
      let checkCount = 0;
      if (self.form_data.permissions.includes(permissions.name)) {
        checkCount++;
      }
      permissions.children.forEach((p) => {
        if (self.form_data.permissions.includes(p.name)) {
          checkCount++;
        }
      });
      if (
        checkCount == permissionLength &&
        !self.form_data.parent_permissions.includes(permissions.name + "-all")
      ) {
        self.form_data.parent_permissions.push(permissions.name + "-all");
      } else {
        let pIndex = self.form_data.parent_permissions.indexOf(
          permissions.name + "-all"
        );
        if (pIndex !== -1) {
          self.form_data.parent_permissions.splice(pIndex, 1);
        }
      }
      self.checkParentPermissionCheckedOrNot();
    },
    checkParentPermissionCheckedOrNot() {
      let self = this;
      let totalParentPermissionCount = self.permissions.length;
      if (
        self.form_data.parent_permissions.length == totalParentPermissionCount
      ) {
        self.form_data.all_permission = true;
      } else {
        self.form_data.all_permission = false;
      }
    },
    checkAllPermissionCheckedOrNot() {
      const self = this;
      self.$nextTick(() => {
        let child_permission = $(".child-permission");
        let child_permission_length = $(child_permission).length;
        let checked_items = 0;
        $(child_permission).each(function () {
          if ($(this).prop("checked")) {
            checked_items++;
          }
        });
        if (child_permission_length == checked_items) {
          self.form_data.all_permission = true;
          $(".all-permission").prop("checked", true);
        } else {
          self.form_data.all_permission = false;
        }
      });
    },
    checkParentPermissionOrNot() {
      const self = this;
      self.$nextTick(() => {
        $(".parent-permission").each(function () {
          let parent_id = $(this).attr("id");
          let child_master_class = $("." + parent_id);
          let child_class_length = $(child_master_class).length;
          let checked_item_length = 0;
          $(child_master_class).each(function () {
            if ($(this).prop("checked")) {
              checked_item_length++;
            }
          });

          if (child_class_length == checked_item_length) {
            self.form_data.parent_permissions.push($("#" + parent_id).val());
          }
        });
      });
    },
    submitForm() {
      const self = this;
      self.$v.$touch();
      if (self.$v.$invalid) {
        self.show_error = true;
        return;
      } else {
        self.show_error = false;
      }

      self.is_loading = true;

      self.form_data._method = self.is_edit ? "PUT" : "POST";
      const url = self.is_edit ? `/roles/${self.model_data.id}` : `/roles`;

      const FormData_obj = window.convertToFormData(self.form_data);

      axios
        .post(url, FormData_obj)
        .then((res) => {
          self.showToastMessage(res.data.message, res.data.status);

          if (res.data.status == "success") {
            window.location = "/roles";
          }
        })
        .catch((err) => {
          self.showToastMessage(
            "Something went wrong! Please try again.",
            "error"
          );
          console.log(err);
        })
        .finally(() => {
          self.is_loading = false;
        });
    },
  },
};
</script>

<style scoped>
.all-permission-heading {
  position: relative;
}

.all-permission-heading input[type="checkbox"] {
  position: absolute;
  left: 50%;
  margin-top: 0;
  top: 50%;
  transform: translate(-50%, -50%);
  opacity: 1;
  pointer-events: fill;
}

.card-header input[type="checkbox"] {
  position: absolute;
  left: 50%;
  margin-top: 8px;
  transform: translate(-50%, -50%);
  opacity: 1;
  pointer-events: fill;
}

.list-group-item input[type="checkbox"] {
  position: absolute;
  left: 50%;
  margin-top: 8px;
  transform: translate(-50%, -50%);
  opacity: 1;
  pointer-events: fill;
}
</style>