<template>
    <div>
        <component @cancel="cancel(index, $event)" @closed="closedDialog(index, $event)" @proceed="proceed(index, $event)" v-for="(dialog, index) in dialogs" :key="index" :is="dialog.component" :options="dialog.options"></component>
    </div>
</template>

<script>
    export default {
        name: "Dialog",
        data() {
            return {
                dialogs: []
            }
        },
        methods: {
            openNewDialog(options) {
                options.isProceed = false;
                this.dialogs.push(options);
            },
            proceed(index, dialog) {
                this.dialogs[index].isProceed = true;
                dialog.closeModal();
                this.resolveDialog(index);
                this.removeDialog(index);
            },
            cancel(index, dialog) {
                this.dialogs[index].isProceed = true;
                dialog.closeModal();
                this.rejectDialog(index);
                this.removeDialog(index);
            },
            resolveDialog(index) {
                this.dialogs[index].options.resolve();
            },
            rejectDialog(index) {
                this.dialogs[index].options.reject();
            },
            removeDialog(index) {
                this.$delete(this.dialogs, index);
            },
            closedDialog(index, dialog) {
                if(this.dialogs[index].isProceed) return;
                this.cancel(index, dialog);
            }
        }
    }
</script>

<style>

</style>
