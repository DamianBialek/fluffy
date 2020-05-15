<template>
    <div ref="modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-html="title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" v-html="message"></div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" @click="cancel">{{buttons.cancel}}</button>
                    <button type="button" class="btn btn-primary" @click="confirm">{{buttons.confirm}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ConfirmDialog",
        props: ['options'],
        data() {
            return {
                buttons: {
                    cancel: "Anuluj",
                    confirm: "Tak"
                },
                onConfirmAction: () => true,
                onCancelAction: () => true,
                visible: false
            }
        },
        computed: {
            modal() {
                return this.$refs.modal
            },
            message() {
                return this.options.message ? this.options.message : '';
            },
            title() {
                return this.options.title ? this.options.title : 'Potwierdzenie';
            },
            isVisible() {
                return this.visible;
            }
        },
        created() {
            if(this.options.buttons) {
                if(this.options.buttons.cancel) {
                    this.buttons.cancel = this.options.buttons.cancel
                }

                if(this.options.buttons.confirm) {
                    this.buttons.confirm = this.options.buttons.confirm
                }
            }
        },
        mounted() {
            this.addModalEventListeners();
            $(this.modal).modal("show")
        },
        methods: {
            addModalEventListeners() {
                $(this.modal).on('hidden.bs.modal', e => {
                    this.visible = false;
                    this.$emit("closed", this)
                }).on('shown.bs.modal', e => {
                    this.visible = true;
                });
            },
            closeModal() {
                $(this.modal).modal("hide");
            },
            confirm() {
                this.$emit("proceed", this)
            },
            cancel() {
                this.$emit("cancel", this)
            }
        }
    }
</script>

<style>

</style>
