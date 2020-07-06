<template>
    <form @submit.prevent="$emit('submit')">
        <div class="form-group row">
            <label for="mark" class="col-sm-2 col-form-label">Marka</label>
            <div class="col-sm-10">
                <input v-model="vehicle.mark" type="text" :class="['form-control', {'is-invalid': fieldHasError('mark')}]" id="mark">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('mark')"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="model" class="col-sm-2 col-form-label">Model</label>
            <div class="col-sm-10">
                <input v-model="vehicle.model" type="text" :class="['form-control', {'is-invalid': fieldHasError('model')}]" id="model">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('model')"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="registration_number" class="col-sm-2 col-form-label">Numer rejestracyjny</label>
            <div class="col-sm-10">
                <input v-model="vehicle.registration_number" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('registration_number')}]" id="registration_number">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('registration_number')"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="vin" class="col-sm-2 col-form-label">Numer vin</label>
            <div class="col-sm-10">
                <input v-model="vehicle.vin" type="text" :class="['form-control', {'is-invalid': fieldHasError('vin')}]" id="vin">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('vin')"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="production_year" class="col-sm-2 col-form-label">Rok rejestracji</label>
            <div class="col-sm-10">
                <input maxlength="4" minlength="4" v-model="vehicle.production_year" step="1" min="1900" max="9999" type="number" :class="['form-control', {'is-invalid': fieldHasError('production_year')}]" id="production_year">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('production_year')"></div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary">Zapisz</button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "CustomerVehicleForm",
        props: {
            vehicle: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        mark: '',
                        model: '',
                        vin: '',
                        registration_number: '',
                        production_year: '',
                    }
                }
            },
            errorFields: {
                type: Object,
                required: false,
                default: () => {}
            }
        },
        data() {
            return {
            }
        },
        computed: {
            fieldHasError() {
                return field => this.errorFields.hasOwnProperty(field)
            },
            fieldInvalidFeedback() {
                return field => {
                    if(!this.fieldHasError(field)) return '';

                    let error = this.errorFields[field];

                    if(Array.isArray(error)) {
                        error = error.join("<br />")
                    }

                    return error;
                }
            }
        }
    }
</script>

<style scoped>

</style>
