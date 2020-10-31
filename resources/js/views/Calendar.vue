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
                    <div :class="['day-box', {'current-day': isCurrentDay(day)}, {'empty': !isDayOfCurrentMonth(day.day)}]" v-for="day in days">
                        <div class="day" v-if="isDayOfCurrentMonth(day.day)">
                            <div class="day-top text-right">{{dayInMonthFromDays(day.day)}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                monthsNames: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"]
            }
        }
    },
    created() {
        this.calendar.dateObj = new Date();
        this.setFirstDayOfMonth();
    },
    computed: {
        daysInMonth() {
            return new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() + 1, 0).getDate();
        },
        days() {
            return [...Array(35)].map((value, index) => {
                return {
                    month: this.calendar.dateObj.getMonth(),
                    year: this.calendar.dateObj.getFullYear(),
                    day: index + 1
                }
            })
        },
        monthHeader() {
            return this.calendar.monthsNames[this.calendar.dateObj.getMonth()] + " " + this.calendar.dateObj.getFullYear();
        },
        currentDate() {
            return new Date();
        }
    },
    methods: {
        setFirstDayOfMonth() {
            const tempDate = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth(), 1);
            let firstMonthDay = tempDate.getDay();
            if (firstMonthDay === 0) {
                firstMonthDay = 7;
            }

            this.calendar.firstMonthDay = firstMonthDay;
        },
        isDayOfCurrentMonth(day) {
            return day - this.calendar.firstMonthDay >= 0 && this.dayInMonthFromDays(day) <= this.daysInMonth;
        },
        dayInMonthFromDays(day) {
            return day - this.calendar.firstMonthDay + 1;
        },
        prevMonth() {
            this.calendar.dateObj = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() - 1, 1);
            this.setFirstDayOfMonth();
        },
        nextMonth() {
            this.calendar.dateObj = new Date(this.calendar.dateObj.getFullYear(), this.calendar.dateObj.getMonth() + 1, 1);
            this.setFirstDayOfMonth();
        },
        isCurrentDay(day) {
            const currentDate = this.currentDate;
            const date = new Date(day.year, day.month, this.dayInMonthFromDays(day.day));
            return currentDate.getFullYear() === date.getFullYear() && currentDate.getMonth() === date.getMonth() && currentDate.getDate() === date.getDate();
        },
        setToday() {
            this.calendar.dateObj = this.currentDate;
            this.setFirstDayOfMonth();
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
