<template>
    <form @submit.prevent="$emit('submit')">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dane</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Samochody</a>
            </li>
        </ul>
        <div class="tab-content m-3" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">Typ</label>
                    <div class="col-sm-10">
                        <select v-model="customer.type" :class="['form-control', {'is-invalid': fieldHasError('type')}]" id="type">
                            <option v-for="type in availableTypes" :value="type">{{type}}</option>
                        </select>
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('type')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Imię</label>
                    <div class="col-sm-10">
                        <input v-model="customer.name" type="text" :class="['form-control', {'is-invalid': fieldHasError('name')}]" id="name">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="surname" class="col-sm-2 col-form-label">Nazwisko</label>
                    <div class="col-sm-10">
                        <input v-model="customer.surname" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('surname')}]" id="surname">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('surname')"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
        </div>

        <div class="text-right">
            <button class="btn btn-primary">Zapisz</button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "CustomerForm",
        props: {
            customer: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        type: '',
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
        data() {
            return {
                availableTypes: [
                    'natural_person',
                    'company'
                ]
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
