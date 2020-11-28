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
                    <div :class="['day-box', {'empty': !isCurrentMonth(day)}, {'current-day': isCurrentDay(day)}]" v-for="day in calendar.days">
                        <div class="day">
                            <div class="day-top text-right">{{day.getDate()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="ordersListModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lista zleceń dla </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
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
            days: []
        }
    },
    created() {
        this.calendar.dateObj = new Date();
        this.init();
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
        grid-template-columns: repeat(7, auto);

        .header-day {
            color: #aaa;
            text-align: center;
        }

        .day-box {
            height: 80px;
            border: 1px solid #ddd;

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
