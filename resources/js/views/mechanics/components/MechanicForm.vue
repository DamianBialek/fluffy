<template>
    <form @submit.prevent="$emit('submit')">
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Imię</label>
            <div class="col-sm-10">
                <input v-model="mechanic.name" type="text" :class="['form-control', {'is-invalid': fieldHasError('name')}]" id="name">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="surname" class="col-sm-2 col-form-label">Nazwisko</label>
            <div class="col-sm-10">
                <input v-model="mechanic.surname" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('surname')}]" id="surname">
                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('surname')"></div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary">Zapisz</button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "MechanicForm",
        props: {
            mechanic: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        name: '',
                        surname: ''
                    }
                }
            },
            errorFields: {
                type: Object,
                required: false,
                default: () => {}
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
