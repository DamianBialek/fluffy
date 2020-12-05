<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{ label }}</label>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-auto">
                    <input v-model="date" type="date" class="form-control" aria-label="Data">
                </div>
                <div class="col-sm-auto minutes-hours-col my-2 my-sm-0">
                    <div class="input-group mb-3">
                        <input v-model="hours" type="number" step="1" min="1" max="24" class="form-control" aria-label="Godzina">
                        <div class="input-group-append">
                            <span class="input-group-text">:</span>
                        </div>
                        <input v-model="minutes" type="number" step="1" min="0" max="59" class="form-control" aria-label="Minuta">
                    </div>
                </div>
                <div class="col-sm-auto">
                    <button @click="setNowOrderReceiptVehicle" type="button" class="btn btn-outline-secondary">Ustaw Teraz</button>
                    <button @click="resetOrderReceiptVehicle" type="button" class="btn btn-outline-secondary">Resetuj</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DateHoursInput",
    data() {
        return {
            date: null,
            hours: null,
            minutes: null,
            fullDate: this.value
        }
    },
    props: {
        label: {
            type: String,
            required: true,
            default: ''
        },
        value: String
    },
    computed: {
        formattedDate() {
            if(!this.date) {
                return null;
            }

            const date = new Date(this.date);
            date.setHours(this.hours);
            date.setMinutes(this.minutes);

            return `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        }
    },
    methods: {
        resetOrderReceiptVehicle() {
            this.date = null;
            this.hours = null;
            this.minutes = null;
            this.fullDate = null;
        },
        setNowOrderReceiptVehicle() {
            const now = new Date();
            this.fullDate = `${now.getFullYear()}-${(now.getMonth() + 1)}-${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
            this.parseOrderReceiptVehicleDateFromApi();
        },
        parseOrderReceiptVehicleDateFromApi() {
            if(!this.fullDate) {
                return;
            }

            const splitted = this.fullDate.split(" ");
            this.date = splitted[0].split("-").map(d => d < 10 ? `0${d}` : d).join("-");

            const splittedHM = splitted[1].split(":");
            this.hours = splittedHM[0];
            this.minutes = splittedHM[1];
        },
        setFullDate() {
            if(!this.date) {
                return null;
            }

            const date = new Date(this.date);
            date.setHours(this.hours);
            date.setMinutes(this.minutes);

            this.fullDate = `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        }
    },
    watch: {
        value() {
            this.fullDate = this.value;
        },
        date() {
            this.setFullDate();
        },
        hours() {
            this.setFullDate();
        },
        minutes() {
            this.setFullDate();
        },
        fullDate() {
            this.parseOrderReceiptVehicleDateFromApi();
            this.$emit("input", this.formattedDate);
        }
    }
}
</script>

<style scoped>

</style>
