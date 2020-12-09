<template>
    <div class="page">
        <section>
            <div class="table-responsive mt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th class="text-center">Wartość</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="parameter in parameters" :key="parameter.id">
                        <td>{{paramName(parameter.name)}}</td>
                        <td class="text-center">{{parameter.value}}</td>
                        <td>
                            <button @click="editParameter(parameter)" v-tooltip="'Edycja'" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <div id="editParameterModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edycja parametru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <label for="parameter" class="input-group-text">{{paramName(editedParameter.name)}}</label>
                            <input id="parameter" type="text" class="form-control" v-model="editedParameter.value">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="updateParameter" type="button" class="btn btn-primary" data-dismiss="modal">Zapisz</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ParametersList",
    data() {
        return {
            names: {
                tax: 'Stawka VAT'
            },
            parameters: [],
            editedParameter: {}
        }
    },
    computed: {
        paramName() {
            return name => this.names[name]
        }
    },
    mounted() {
        this.getParameters();
        $("#editParameterModal").on('hidden.bs.modal', () => {
            this.editedParameter = {};
        });
    },
    methods: {
        getParameters() {
            this.setLoading(true);
            this.$api.get("/api/parameters")
                .then(res => {
                    this.parameters = res.data.data.parameters;
                    this.setLoading(false);
                })
        },
        editParameter(parameter) {
            this.editedParameter = parameter;
            $("#editParameterModal").modal("show");
        },
        updateParameter() {
            if(this.editedParameter.name === 'tax') {
                this.editedParameter.value = this.editedParameter.value.replace(/[\D]+/g, '');
            }
            this.$api.put(`/api/parameters/${this.editedParameter.id}`, this.editedParameter)
                .then(res => {
                    if(res.data.success) {
                        this.$notify("Pomyślnie zaktualizowano dane !", "", "success").then(() => {
                            this.getParameters();
                        })
                    }
                })
                .catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.$notify("Wystąpił błąd podczas aktualizacji danych !", "", "error");
                })
                .finally(() => {
                    this.setLoading(false);
                })
        }
    }
}
</script>

<style>

</style>
