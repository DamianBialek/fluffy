<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'mechanicsList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <form @submit.prevent="saveMechanic">
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
        </section>
    </div>
</template>

<script>
    export default {
        name: "MechanicsAddMechanic",
        data() {
            return {
                mechanic: {
                    name: '',
                    surname: ''
                },
                errorFields: {}
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
        },
        methods: {
            saveMechanic() {
                this.errorFields = {};
                this.$api.post("/api/mechanics", this.mechanic)
                .then(res => {
                    if(res.data.success) {
                        this.$router.push({name: 'mechanicsList'})
                    }
                })
                .catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.errorFields = err.response.data.data.fields;
                })
            }
        }
    }
</script>

<style>

</style>
