<template>
    <div class="page">
        <section>
            <div class="calendar">
                <div class="calendar-header">
                    <div class="row">
                        <div class="navigations col">
                            <div class="btn-group" role="group">
                                <button @click="prevMonth" type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i></button>
                                <button @click="nextMonth" type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-right"></i></button>
                                <button @click="setToday" type="button" class="btn btn-primary">Dzisiaj</button>
                            </div>
                        </div>
                        <div class="month-name col">{{monthHeader}}</div>
                        <div class="col"></div>
                    </div>
                </div>
                <div class="calendar-days">
                    <div class="header-day" v-for="headerDay in calendar.header">{{headerDay}}</div>
                    <div :class="['day-box', {'empty': !isCurrentMonth(day)}, {'current-day': isCurrentDay(day)}]" v-for="day in calendar.days" @click="openOrdersListModal(day)">
                        <div class="day">
                            <div class="day-top text-right">{{day.getDate()}}</div>
                            <div class="day-orders">
                                <router-link :to="{name: 'ordersEdit', params: {id: order.id}}" class="badge badge-success d-block my-2" v-for="order in getOrdersByDate(day)" :key="order.id">{{order.number}}</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="ordersListModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lista zleceń dla {{`${selectedDate.getDate()}/${selectedDate.getMonth() + 1}/${selectedDate.getFullYear()}`}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive mt-3">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Numer</th>
                                    <th>Status</th>
                                    <th>Nazwa</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="order in getOrdersByDate(selectedDate)" :key="order.id">
                                    <th scope="row">{{order.id}}</th>
                                    <th>{{order.number}}</th>
                                    <th>{{order.state_name}}</th>
                                    <td>{{order.name && order.name.length ? order.name : '---'}}</td>
                                    <td class="text-center">
                                        <router-link v-tooltip="'Podgląd'" tag="button" :to="{name: 'ordersEdit', params: {id: order.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-search"></i></router-link>
                                        <a v-tooltip="'Zobacz fakturę'" v-if="order.invoice_number" target="_blank" :href="`/orders/${order.id}/invoice`" class="btn btn-outline-secondary ml-2"><i class="far fa-file-pdf"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="!getOrdersByDate(selectedDate).length">
                                    <td colspan="4" class="text-center"><strong>Brak zleceń</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button @click="createNewOrder" type="button" class="btn btn-outline-secondary"><i class="fa fa-plus text-success mr-2" />Dodaj nowe zlecenie</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Calendar",
    data() {
        return {
            calendar: {
                dateObj: null,
                firstMonthDay: null,
                header: ["Pon", "Wto", "Śro", "Czw", "Pią", "Sob", "Nie"],
                monthsNames: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
                maxWeeks: 6,
                days: [],
                prevMonth: null,
                nextMonth: null,
                daysInMonth: null
            },
            activeDate: null,
            days: [],
            orders: [],
            selectedDate: new Date()
        }
    },
    created() {
        this.calendar.dateObj = new Date();
        this.init();
    },
    mounted() {
        this.setLoading(true);
        this.$api.get("/api/orders")
            .then(res => {
                this.orders = res.data.data.orders;
                this.orders.map(o => {
                    return Object.assign(o, {
                        start_date: this.parseDateFromApi(o.start_date)
                    })
                })
                this.setLoading(false);
            })
    },
    computed: {
        monthHeader() {
            return this.calendar.monthsNames[this.calendar.dateObj.getMonth()] + " " + this.calendar.dateObj.getFullYear();
        },
        currentDate() {
            return new Date();
        }
    },
    methods: {
        init() {
            this.setFirstDayOfMonth();
            this.calendar.prevMonth = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() - 1, 1);
            this.calendar.nextMonth = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() + 1, 1);
            this.calendar.daysInMonth = this.daysInMonth(this.calendar.dateObj)
            const days = this.calendar.maxWeeks * 7;
            this.calendar.days = [...Array(days)].map((v, i) => {
                i += 1;
                return new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth(), i - this.calendar.firstMonthDay + 1);
            })
        },
        setFirstDayOfMonth() {
            const tempDate = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth(), 1);
            let firstMonthDay = tempDate.getDay();
            if (firstMonthDay === 0) {
                firstMonthDay = 7;
            }

            this.calendar.firstMonthDay = firstMonthDay;
        },
        isCurrentMonth(date) {
            return date.getMonth() === this.calendar.dateObj.getMonth();
        },
        dayInMonthFromDays(day) {
            return day - this.calendar.firstMonthDay + 1;
        },
        prevMonth() {
            this.calendar.dateObj = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() - 1, 1);
            this.init()
        },
        nextMonth() {
            this.calendar.dateObj = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() + 1, 1);
            this.init()
        },
        isCurrentDay(date) {
            const currentDate = this.currentDate;
            return currentDate.getFullYear() === date.getFullYear() && currentDate.getMonth() === date.getMonth() && currentDate.getDate() === date.getDate();
        },
        setToday() {
            this.calendar.dateObj = this.currentDate;
            this.init();
        },
        daysInMonth(dateObj) {
            return new Date(dateObj.getFullYear(), dateObj.getMonth() + 1, 0).getDate();
        },
        parseDateFromApi(date) {
            if(!date) {
                return null;
            }
            const splitted = date.split(" ");
            return new Date(splitted[0]);
        },
        getOrdersByDate(date) {
            if(!this.orders.length) {
                return [];
            }

            const orders = [];

            this.orders.forEach(o => {
               if(o.start_date) {
                   if(date.getDate() == o.start_date.getDate() && date.getMonth() == o.start_date.getMonth() && date.getYear() == o.start_date.getYear()) {
                       orders.push(o)
                   }
               }
            });

            return orders;
        },
        openOrdersListModal(day) {
            this.selectedDate = day;
            $("#ordersListModal").modal("show");
        },
        createNewOrder() {
            const date = `${this.selectedDate.getFullYear()}-${this.selectedDate.getMonth() + 1}-${this.selectedDate.getDate()} 0:0:0`;
            this.$router.push({name: 'ordersAdd', query: {date: date}})
        }
    }
}
</script>

<style lang="scss">
    .calendar-header {
        padding: 0 15px;

        .month-name {
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }
    }

    .calendar-days {
        margin-top: 1rem;
        display: grid;
        grid-template-columns: repeat(7, 1fr);

        .header-day {
            color: #aaa;
            text-align: center;
        }

        .day-box {
            height: 80px;
            border: 1px solid #ddd;

            .day-orders {
                overflow-y: auto;
                max-height: 45px;
                margin-top: 5px;
                padding: 0 10px;
            }

            .day-top {
                height: 20px;
            }

            .day {
                padding: 4px;
            }

            &.current-day {
                border: 2px solid $gray;
            }

            &.empty {
                background-color: #f3f3f4;
            }

            &:not(.empty) {
                &:hover {
                    border-color: $turquoise;
                    border-width: 2px;
                }
            }
        }
    }
</style>
