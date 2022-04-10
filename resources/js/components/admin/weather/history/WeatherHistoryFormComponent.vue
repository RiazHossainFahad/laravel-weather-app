<template>
  <form @submit.prevent="submitForm()">
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="city"
            >City <span class="text-danger">*</span></label
          >
          <div>
            <select class="form-control select2" v-model="form_data.city">
              <option v-for="(city, i) in cities" :key="i" :value="city">
                {{ city }}
              </option>
            </select>
            <required-component
              v-if="show_error && !$v.form_data.city.required"
            />
            <minlength-component
              v-if="show_error && !$v.form_data.city.minLength"
              :min_length="$v.form_data.city.$params.minLength.min"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="lat"
            >Latitude <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.lat"
            />
            <required-component
              v-if="show_error && !$v.form_data.lat.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="lon"
            >Longitude <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.lon"
            />
            <required-component
              v-if="show_error && !$v.form_data.lon.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="weather_condition"
            >Weather Condition <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="text"
              class="form-control"
              v-model="form_data.weather_condition"
            />
            <required-component
              v-if="show_error && !$v.form_data.weather_condition.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="temperature"
            >Temperature(&#8451;) <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.temperature"
            />
            <required-component
              v-if="show_error && !$v.form_data.temperature.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="temperature_feel_like"
            >Temperature(&#8451;) feel like<span class="text-danger"
              >*</span
            ></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.temperature_feel_like"
            />
            <required-component
              v-if="show_error && !$v.form_data.temperature_feel_like.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="humidity"
            >Humidity <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.humidity"
            />
            <required-component
              v-if="show_error && !$v.form_data.humidity.required"
            />
          </div>
        </div>
      </div>
      <!--end col-->

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label" for="wind_speed"
            >Wind Speed(km/h) <span class="text-danger">*</span></label
          >
          <div>
            <input
              type="number"
              step="any"
              class="form-control"
              v-model="form_data.wind_speed"
            />
            <required-component
              v-if="show_error && !$v.form_data.wind_speed.required"
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
          <span v-else>{{
            !is_edit ? "Create Weather Record" : "Update Weather Record"
          }}</span>
        </button>
        <a href="/" class="btn btn-danger" :disabled="is_loading">
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
  name: "WeatherHistoryFormComponent",
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
    all_cities: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      form_data: {
        city: null,
        lat: null,
        lon: null,
        weather_condition: null,
        temperature: null,
        temperature_feel_like: null,
        humidity: null,
        wind_speed: null,
      },
      cities: [],
      show_error: false,
      is_loading: false,
    };
  },
  validations: {
    form_data: {
      city: {
        required,
        minLength: minLength(3),
      },
      lat: {
        required,
      },
      lon: {
        required,
      },
      weather_condition: {
        required,
      },
      temperature: {
        required,
      },
      temperature_feel_like: {
        required,
      },
      humidity: {
        required,
      },
      wind_speed: {
        required,
      },
    },
  },
  mounted() {
    this.cities = this.all_cities;

    if (this.is_edit) {
      this.form_data = this.model_data;
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
      const url = self.is_edit
        ? `/weather-histories/${self.model_data.id}`
        : `/weather-histories`;

      const FormData_obj = window.convertToFormData(self.form_data);

      axios
        .post(url, FormData_obj)
        .then((res) => {
          self.showToastMessage(res.data.message, res.data.status);

          if (res.data.status == "success") {
            window.location = "/";
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