<template>
    <div class="page">
        <section>
            <form @submit="submit">
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Nazwa</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" v-model="user.name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" v-model="user.email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Hasło</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" v-model="user.password">
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary" type="submit">Zapisz</button>
                </div>
            </form>
        </section>
    </div>
</template>

<script>
import {getLoggedInUser} from "../../helpers/auth";

export default {
    name: "UserEdit",
    data() {
        return {
            user: getLoggedInUser()
        }
    },
    mounted() {
        this.$set(this.user, "password", "");
    },
    methods: {
        submit(e) {
            e.preventDefault();
            this.$api.put("/api/user", this.user)
            .then(() => {
                this.$notify("Pomyślnie zaktualizowano dane ! Proszę ponownie się zalogować", "", "success").then(() => {
                    this.$store.dispatch("logout");
                    this.$router.push({name: 'login'})
                })
            })
        }
    }
}
</script>

<style>

</style>
