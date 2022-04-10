<template>
  <form @submit.prevent="submitForm()">
    <div class="row mt-4">
      <div class="col-md-7">
        <div class="mb-3">
          <label class="form-label" for="name"
            >Name <span class="text-danger">*</span></label
          >
          <div>
            <input type="text" class="form-control" v-model="form_data.name" />
            <required-component
              v-if="show_error && !$v.form_data.name.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-7">
        <div class="mb-3">
          <label class="form-label" for="email"
            >Email Address <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="email"
              class="form-control"
              v-model="form_data.email"
            />
            <required-component
              v-if="show_error && !$v.form_data.email.required"
            />
            <invalid-component v-if="show_error && !$v.form_data.email.email" />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-7">
        <div class="mb-3">
          <label class="form-label" for="password"
            >Password <span v-if="!is_edit" class="text-danger">*</span></label
          >
          <div>
            <input
              type="password"
              class="form-control"
              v-model="form_data.password"
            />
            <required-component
              v-if="show_error && !$v.form_data.password.required"
            />
            <minlength-component
              v-if="show_error && !$v.form_data.password.minLength"
              :min_length="$v.form_data.password.$params.minLength.min"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-7">
        <div class="mb-3">
          <label class="form-label" for="password_confirmation"
            >Re-type Password
            <span v-if="!is_edit" class="text-danger">*</span></label
          >
          <div>
            <input
              type="password"
              class="form-control"
              v-model="form_data.password_confirmation"
            />
            <invalid-component
              v-if="
                show_error && !$v.form_data.password_confirmation.sameAsPassword
              "
              message="Password mismatch"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-7">
        <div class="mb-3">
          <label class="form-label" for="roles"
            >Roles <span class="text-danger">*</span></label
          >
          <div>
            <Select2
              id="roles"
              v-model="form_data.roles"
              :settings="{
                multiple: true,
              }"
              :options="
                roles.map((item) => {
                  return {
                    id: item.name,
                    text: item.name,
                  };
                })
              "
            />
            <required-component
              v-if="show_error && !$v.form_data.roles.required"
            />
            <minlength-component
              v-if="show_error && !$v.form_data.roles.minLength"
              :min_length="$v.form_data.roles.$params.minLength.min"
            />
          </div>
        </div>
      </div>
      <!--end col-->
    </div>
    <!--end row-->

    <div class="row mt-3">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-dark" :disabled="is_loading">
          <span v-if="is_loading">Loading...</span>
          <span v-else>{{ !is_edit ? "Create User" : "Update User" }}</span>
        </button>
        <a href="/users" class="btn btn-danger" :disabled="is_loading">
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
import {
  required,
  minLength,
  email,
  sameAs,
  requiredIf,
} from "vuelidate/lib/validators";
import RequiredComponent from "@/components/shared/error/RequiredComponent";
import MinlengthComponent from "@/components/shared/error/MinlengthComponent";
import InvalidComponent from "@/components/shared/error/InvalidComponent";

export default {
  name: "UserFormComponent",
  components: { RequiredComponent, MinlengthComponent, InvalidComponent },
  props: {
    is_edit: {
      type: Boolean,
      default: false,
    },
    model_data: {
      type: Object,
      default: {},
    },
    all_roles: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      form_data: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        roles: [],
      },
      roles: [],
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
      email: {
        required,
        email,
      },
      password: {
        required: requiredIf(function (model) {
          return !this.is_edit;
        }),
        minLength: minLength(8),
      },
      password_confirmation: {
        minLength: minLength(8),
        sameAsPassword: sameAs("password"),
      },
      roles: {
        required,
        minLength: minLength(1),
      },
    },
  },
  mounted() {
    this.roles = this.all_roles;

    if (this.is_edit) {
      this.form_data = this.model_data;
      this.form_data.roles = this.model_data.assigned_roles;
    }
  },
  methods: {
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
      const url = self.is_edit ? `/users/${self.model_data.id}` : `/users`;

      const FormData_obj = window.convertToFormData(self.form_data);

      axios
        .post(url, FormData_obj)
        .then((res) => {
          self.showToastMessage(res.data.message, res.data.status);

          if (res.data.status == "success") {
            window.location = "/users";
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
</style>