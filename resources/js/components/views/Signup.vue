<template>
    <v-app>
        <views-navigation :color="color" :flat="flat" />
        <v-container fluid fill-height>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-container
                        width="80vw"
                        max-width="100vw"
                        min-width="400"
                        fluid
                    >
                        <v-row justify="center">
                            <v-col cols="12" lg="8" class="elevation-4">
                                <v-row justify="center" class="text-center">
                                    <h1
                                        class="pa-5 font-weight-bold primary--text"
                                    >
                                        Register
                                    </h1>
                                </v-row>
                                <v-row justify="center">
                                    <v-col cols="12" md="9">
                                        <v-card class="transparent elevation-0">
                                            <v-container>
                                                <v-form
                                                    ref="form"
                                                    lazy-validation
                                                >
                                                    <v-row justify="center">
                                                        <v-col cols="12" md="6"
                                                            ><v-text-field
                                                                v-model="
                                                                    user.name
                                                                "
                                                                :error-messages="
                                                                    formSignUpError.name
                                                                "
                                                                :rules="
                                                                    rules.required
                                                                "
                                                                label="Name"
                                                            ></v-text-field
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-text-field
                                                                v-model="
                                                                    user.number
                                                                "
                                                                :error-messages="
                                                                    formSignUpError.number
                                                                "
                                                                :rules="
                                                                    rules.contactRules
                                                                "
                                                                prefix="(+63)"
                                                                maxlength="10"
                                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                label="Contact Number"
                                                            ></v-text-field
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-text-field
                                                                v-model="
                                                                    user.relative_name
                                                                "
                                                                :error-messages="
                                                                    formSignUpError.relative_name
                                                                "
                                                                :rules="
                                                                    rules.required
                                                                "
                                                                label="Relative/Gaurdian Name"
                                                            ></v-text-field
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-text-field
                                                                v-model="
                                                                    user.relative_contact
                                                                "
                                                                :error-messages="
                                                                    formSignUpError.relative_contact
                                                                "
                                                                :rules="
                                                                    rules.contactRules
                                                                "
                                                                prefix="(+63)"
                                                                maxlength="10"
                                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                label="Relative/Gaurdian Contact Number"
                                                            ></v-text-field
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-select
                                                                :items="[
                                                                    'MALE',
                                                                    'FEMALE',
                                                                ]"
                                                                v-model="
                                                                    user.gender
                                                                "
                                                                :error-messages="
                                                                    formSignUpError.gender
                                                                "
                                                                :rules="
                                                                    rules.required
                                                                "
                                                                label="Gender"
                                                            ></v-select
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-dialog
                                                                ref="birthdateDialog"
                                                                v-model="
                                                                    birthdateDialog
                                                                "
                                                                :return-value.sync="
                                                                    user.birthdate
                                                                "
                                                                width="290px"
                                                            >
                                                                <template
                                                                    v-slot:activator="{
                                                                        on,
                                                                    }"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            user.birthdate
                                                                        "
                                                                        label="Birthday"
                                                                        readonly
                                                                        v-on="
                                                                            on
                                                                        "
                                                                    />
                                                                </template>
                                                                <v-date-picker
                                                                    v-model="
                                                                        user.birthdate
                                                                    "
                                                                    color="primary"
                                                                    scrollable
                                                                    dark
                                                                >
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn
                                                                        text
                                                                        color="primary"
                                                                        @click="
                                                                            birthdateDialog = false
                                                                        "
                                                                        >Cancel</v-btn
                                                                    >
                                                                    <v-btn
                                                                        text
                                                                        color="primary"
                                                                        @click="
                                                                            $refs.birthdateDialog.save(
                                                                                user.birthdate
                                                                            )
                                                                        "
                                                                        >OK</v-btn
                                                                    >
                                                                </v-date-picker>
                                                            </v-dialog></v-col
                                                        >
                                                        <v-col
                                                            cols="12"
                                                            md="12"
                                                        >
                                                            <v-text-field
                                                                type="text"
                                                                :error-messages="
                                                                    formSignUpError.address
                                                                "
                                                                v-model="
                                                                    user.address
                                                                "
                                                                label="Address"
                                                            />
                                                        </v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-select
                                                                :items="[
                                                                    'COVID',
                                                                    'NON_COVID',
                                                                ]"
                                                                v-model="
                                                                    user.type
                                                                "
                                                                :rules="
                                                                    rules.required
                                                                "
                                                                label="Type"
                                                            ></v-select
                                                        ></v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-select
                                                                :items="[
                                                                    'VACCINATED',
                                                                    'NON_VACCINATED',
                                                                ]"
                                                                v-model="
                                                                    user.vaccination
                                                                "
                                                                :rules="
                                                                    rules.required
                                                                "
                                                                label="Vaccination Status"
                                                            ></v-select
                                                        ></v-col>
                                                        <v-col cols="12" md="6">
                                                            <v-text-field
                                                                type="text"
                                                                :error-messages="
                                                                    formSignUpError.username
                                                                "
                                                                v-model="
                                                                    user.username
                                                                "
                                                                label="Username"
                                                            />
                                                        </v-col>
                                                        <v-col cols="12" md="6"
                                                            ><v-text-field
                                                                v-model="
                                                                    user.password
                                                                "
                                                                label="Password"
                                                                id="password"
                                                                name="password"
                                                                :append-icon="
                                                                    visible
                                                                        ? 'mdi-eye-off'
                                                                        : 'mdi-eye'
                                                                "
                                                                @click:append="
                                                                    visible =
                                                                        !visible
                                                                "
                                                                :rules="
                                                                    rules.passwordRules
                                                                "
                                                                :type="
                                                                    visible
                                                                        ? 'text'
                                                                        : 'password'
                                                                "
                                                                @keydown.enter="
                                                                    register()
                                                                "
                                                            ></v-text-field
                                                        ></v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-container>
                                            <v-card-actions>
                                                <v-btn
                                                    color="primary"
                                                    large
                                                    block
                                                    @click="
                                                        register(),
                                                            (loader = 'loading')
                                                    "
                                                    class="ma-2"
                                                    :loading="loading"
                                                    :disabled="loading"
                                                >
                                                    Sign-up
                                                    <template v-slot:loader>
                                                        <span
                                                            class="custom-loader"
                                                        >
                                                            <v-icon light
                                                                >mdi-cached</v-icon
                                                            >
                                                        </span>
                                                    </template>
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
export default {
    name: "app",

    data() {
        return {
            loading: false,
            birthdateDialog: false,
            user: {
                name: null,
                number: null,
                address: null,
                status: null,
                birthdate: new Date().toISOString().substr(0, 10),
                username: null,
                password: null,
            },
            visible: false,
            formSignUpError: {
                name: null,
                number: null,
                address: null,
                status: null,
                username: null,
                password: null,
            },
            rules: {
                required: [
                    (v) => !!v || "Field is required",
                    (v) =>
                        (!!v && v.length <= 255) ||
                        " Field should not be more than 255 characters",
                ],
                emailRules: [
                    (v) => !!v || "E-mail is required",
                    (v) =>
                        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                        "E-mail must be valid",
                    (v) =>
                        (!!v && v.length <= 255) ||
                        "E-mail must not be more than 255 characters",
                ],
                contactRules: [
                    (v) => !!v || "Contact Number is required",
                    (v) =>
                        (!!v && v.length >= 10) ||
                        "Contact Number must be 11 characters",
                    (v) =>
                        (!!v && v.length < 11) ||
                        "Contact Number must not be more than 11 characters",
                ],
                passwordRules: [
                    (v) => !!v || "Password is required",
                    (v) =>
                        (!!v && v.length >= 8) ||
                        "Password must be more than 8 characters",
                ],
            },

            fab: null,
            color: "",
            flat: null,
        };
    },

    methods: {
        register() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                axios
                    .post("/api/v1/register", {
                        ...this.user,
                        status: "GUEST",
                        password_confirmation: this.user.password,
                    })
                    .then((response) => {
                        axios
                            .post("/api/v1/login", {
                                username: this.user.username,
                                password: this.user.password,
                            })
                            .then((response) => {
                                if (response.data.errors) {
                                    this.error = response.data.errors;
                                    return;
                                }
                                let token = response.data.token;
                                let user_id = response.data.user.id;
                                let profile_id = response.data.occupant.id;
                                let user_type = response.data.user.role;

                                // Create a local storage item
                                sessionStorage.setItem("user-token", token);
                                sessionStorage.setItem("user-type", user_type);
                                sessionStorage.setItem("user-id", user_id);
                                sessionStorage.setItem(
                                    "profile-id",
                                    profile_id
                                );

                                // Redirect user
                                if (user_type == "OCCUPANT") {
                                    sessionStorage.setItem(
                                        "occupant-name",
                                        response.data.occupant.name
                                    );
                                    sessionStorage.setItem(
                                        "occupant-status",
                                        response.data.occupant.status
                                    );
                                    this.$router.push("/");
                                }

                                swal.fire({
                                    position: "top-end",
                                    toast: true,
                                    type: "success",
                                    icon: "success",
                                    text: "Successfully Registered",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            })
                            .catch((error) => {
                                if (
                                    error.response.data.message ==
                                    "Unauthenticated."
                                ) {
                                    sessionStorage.clear();
                                    this.$router.push("/login");
                                    swal.fire(
                                        "Error!",
                                        error.response.data.message,
                                        "error"
                                    );
                                } else {
                                    swal.fire(
                                        "Invalid Credentials!",
                                        error,
                                        "error"
                                    );
                                }
                            })
                            .finally((x) => {
                                this.loading = false;
                            });
                    })
                    .catch((error) => {
                        if (error.response.status == 422) {
                            this.formSignUpError = error.response.data.errors;
                            this.loading = false;
                        } else {
                            console.log(error);
                        }
                    })
                    .finally((x) => {});
            }
        },

        onScroll(e) {
            if (typeof window === "undefined") return;
            const top = window.pageYOffset || e.target.scrollTop || 0;
            this.fab = top > 60;
        },

        toTop() {
            this.$vuetify.goTo(0);
        },
    },

    watch: {
        fab(value) {
            if (value) {
                this.color = "#f8f9fa";
                this.flat = false;
            } else {
                this.color = "transparent";
                this.flat = true;
            }
        },
    },

    created() {
        this.toTop();
        const top = window.pageYOffset || 0;
        if (top <= 60) {
            this.color = "transparent";
            this.flat = true;
        }
    },

    beforeRouteEnter(to, from, next) {
        if (sessionStorage.getItem("user-type")) {
            if (sessionStorage.getItem("user-type") == "ADMINISTRATOR") {
                return next("admin/dashboard");
            } else if (sessionStorage.getItem("user-type") == "SUBSCRIBER") {
                return next("/dashboard");
            }
        }
        next();
    },
};
</script>
<style>
.custom-loader {
    animation: loader 1s infinite;
    display: flex;
}
@-moz-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@-webkit-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@-o-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

#otp span {
    color: rgb(71, 77, 87) !important;
    font-weight: normal !important;
}

#otp span strong {
    color: #f59b21;
    font-weight: bold !important;
}

.otp-input {
    width: 60px;
    height: 60px;
    padding: 5px;
    margin: 0 10px;
    font-size: 30px;
    border-radius: 4px;
    border: 1px solid rgba(0, 0, 0, 0.3);
    font-family: "Rubik", sans-serif !important;
}

.error {
    border: 1px solid red !important;
}

input:focus.otp-input {
    outline: 2px solid #f59b21 !important;
}

/* textarea:focus,
input:focus {
    outline: none;
} */

.otp-input::-webkit-inner-spin-button,
.otp-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
