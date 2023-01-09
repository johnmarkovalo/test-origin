<template>
    <v-container fluid>
        <!-- <v-overlay :value="componentOverlay">
            <v-progress-circular
                :size="100"
                :width="5"
                indeterminate
            ></v-progress-circular>
        </v-overlay> -->
        <v-row justify-center wrap>
            <v-col md10>
                <v-card class="elevation-9 ma-4">
                    <v-container>
                        <v-data-table
                            :loading="tableLoading"
                            loading-text="Fetching isolation list... Please wait"
                            :headers="tableIsolationHeaders"
                            :items="tableIsolations"
                            :search="tableSearch"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title class="headline"
                                        >Isolations</v-toolbar-title
                                    >
                                    <div class="flex-grow-1"></div>
                                    <v-btn
                                        small
                                        @click="formIsolationDialog = true"
                                        color="primary"
                                    >
                                        <v-icon small left
                                            >mdi-plus-circle</v-icon
                                        >
                                        Add Isolation
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.id="{ item }">
                                {{
                                    tableIsolations
                                        .map(function (x) {
                                            return x.id;
                                        })
                                        .indexOf(item.id) + 1
                                }}
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="editIsolation(item)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon @click="deleteIsolation(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="formIsolationDialog" max-width="800px" persistent>
            <v-card>
                <v-overlay :value="componentOverlay">
                    <v-progress-circular
                        :size="100"
                        :width="5"
                        indeterminate
                    ></v-progress-circular>
                </v-overlay>
                <v-card-title class="headline">
                    {{ formIsolationTitle }}
                </v-card-title>
                <v-card-text>
                    <v-form ref="isolationForm" lazy-validation>
                        <v-row justify="center">
                            <v-col cols="12" md="6">
                                <v-text-field
                                    type="text"
                                    :error-messages="
                                        formIsolationErrors.username
                                    "
                                    v-model="
                                        editedIsolationInformation.user.username
                                    "
                                    :rules="rules.required"
                                    label="Username"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="
                                        editedIsolationInformation.user.password
                                    "
                                    label="Password"
                                    id="password"
                                    name="password"
                                    prepend-icon="fa-lock"
                                    :append-icon="
                                        visible ? 'mdi-eye-off' : 'mdi-eye'
                                    "
                                    @click:append="visible = !visible"
                                    :rules="rules.passwordRules"
                                    :type="visible ? 'text' : 'password'"
                                    @keydown.enter="login()"
                                />
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="12" md="6">
                                <v-text-field
                                    type="text"
                                    :rules="rules.required"
                                    :error-messages="formIsolationErrors.name"
                                    v-model="editedIsolationInformation.name"
                                    label="Name"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    type="text"
                                    :rules="rules.required"
                                    :error-messages="formIsolationErrors.number"
                                    v-model="editedIsolationInformation.number"
                                    label="Number"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    type="text"
                                    :rules="rules.required"
                                    :error-messages="formIsolationErrors.tel_no"
                                    v-model="editedIsolationInformation.tel_no"
                                    label="Telephone Number"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select
                                    :items="statuses"
                                    :rules="rules.required"
                                    :error-messages="formIsolationErrors.status"
                                    v-model="editedIsolationInformation.status"
                                    label="Status"
                                />
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    :rules="rules.required"
                                    type="text"
                                    :error-messages="
                                        formIsolationErrors.address
                                    "
                                    v-model="editedIsolationInformation.address"
                                    label="Address"
                                />
                            </v-col>
                        </v-row>
                        <v-row
                            ><v-col cols="12">
                                <GmapMap
                                    style="width: 100%; height: 400px"
                                    :zoom="25"
                                    :center="center"
                                >
                                    <GmapMarker
                                        @drag="changed"
                                        label="★"
                                        :draggable="true"
                                        :position="address"
                                    /> </GmapMap
                            ></v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="px-12" text @click="closeIsolationForm"
                        >Cancel</v-btn
                    >
                    <v-btn class="px-12" @click="saveIsolation" color="primary"
                        >Save</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            componentOverlay: false,
            // profileId: sessionStorage.getItem("profile-id"),
            tableLoading: true,
            tableSearch: null,
            statuses: ["RECEIVING", "NOT_RECEIVING"],
            searchInput: "",
            visible: false,
            //Isolation
            tableIsolations: [],
            formIsolationDialog: false,
            formIsolationListDialog: false,

            formIsolationErrors: {
                name: null,
                address: null,
                number: null,
                user: { username: null, password: null },
                lat: 6.9214,
                lng: 122.079,
            },

            tableIsolationHeaders: [
                { text: "ID", value: "id" },
                { text: "Name", value: "name" },
                { text: "Address", value: "address" },
                { text: "Phone Number", value: "number" },
                { text: "Telephone Number", value: "tel_no" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    align: "center",
                },
            ],

            editedIsolationIndex: -1,
            editedIsolationInformation: {
                name: null,
                address: null,
                number: null,
                user: { username: null, password: null },
                latitude: 6.9214,
                longitude: 122.079,
            },
            defaultIsolationInformation: {
                name: null,
                address: null,
                number: null,
                user: { username: null, password: null },
                latitude: 6.9214,
                longitude: 122.079,
            },

            //Google Maps Variables
            center: { lat: 6.9214, lng: 122.079 },
            address: { lat: 6.9214, lng: 122.079 },

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
                isolationRules: [
                    (v) => !!v || "Isolation Number is required",
                    (v) =>
                        (!!v && v.length >= 10) ||
                        "Isolation Number must be 11 characters",
                    (v) =>
                        (!!v && v.length < 11) ||
                        "Isolation Number must not be more than 11 characters",
                ],
                passwordRules: [
                    (v) => !!v || "Password is required",
                    (v) =>
                        (!!v && v.length >= 8) ||
                        "Password must be more than 8 characters",
                ],
            },
        };
    },

    computed: {
        formIsolationTitle() {
            return this.editedIsolationIndex === -1
                ? "New Isolation"
                : "Edit Isolation";
        },
    },

    mounted() {
        this.fetchIsolations();
    },

    methods: {
        //Isolation
        fetchIsolations() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/isolations")
                .then((response) => {
                    this.tableIsolations = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        saveIsolation() {
            // Validate isolationForm
            if (this.$refs.isolationForm.validate()) {
                this.componentOverlay = true;
                if (this.editedIsolationIndex > -1) {
                    this.updateIsolation();
                } else {
                    this.createIsolation();
                }
            }
        },

        createIsolation() {
            axios
                .post("/api/v1/isolations", {
                    ..._.omit(this.editedIsolationInformation, "user"),
                    ...this.editedIsolationInformation.user,
                    password_confirmation:
                        this.editedIsolationInformation.user.password,
                })
                .then((response) => {
                    this.fetchIsolations();
                    this.closeIsolationForm();
                    swal.fire({
                        position: "top-end",
                        toast: true,
                        type: "success",
                        icon: "success",
                        text: "Successfully Created",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                })
                .catch((error) => {
                    this.componentOverlay = false;

                    if (error.response.status == 422) {
                        this.formIsolationErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        editIsolation(isolation) {
            this.address = this.center = {
                lat: isolation.latitude,
                lng: isolation.longitude,
            };

            this.editedIsolationIndex = this.tableIsolations.indexOf(isolation);
            this.editedIsolationInformation = Object.assign({}, isolation);
            this.formIsolationDialog = true;
        },

        updateIsolation() {
            axios
                .put(
                    "/api/v1/isolations/" + this.editedIsolationInformation.id,
                    {
                        ..._.omit(this.editedIsolationInformation, "user"),
                        ...this.editedIsolationInformation.user,
                        password_confirmation:
                            this.editedIsolationInformation.user.password,
                    }
                )
                .then((response) => {
                    this.fetchIsolations();
                    this.closeIsolationForm();
                    swal.fire({
                        position: "top-end",
                        toast: true,
                        type: "success",
                        icon: "success",
                        text: "Successfully Updated",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                })
                .catch((error) => {
                    this.componentOverlay = false;

                    if (error.response.status == 422) {
                        this.formIsolationErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        deleteIsolation(isolation) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            })
                .then((result) => {
                    if (result.value) {
                        axios
                            .delete("/api/v1/isolations/" + isolation.id)
                            .then(() => {
                                this.fetchIsolations();
                                this.closeIsolationForm();
                                swal.fire(
                                    "Deleted!",
                                    "Isolation has been deleted.",
                                    "success"
                                );
                            })
                            .catch((error) => {
                                swal.fire(
                                    "Failed!",
                                    "There was something wrong.",
                                    "warning"
                                );
                                this.error = response.data.errors;
                                if (
                                    error.response.data.message ==
                                    "Unauthenticated."
                                ) {
                                    sessionStorage.clear();
                                    this.$router.push("/login");
                                }
                                return;
                            });
                    }
                })
                .catch(() => {
                    swal("Failed!", "There was something wrong.", "warning");
                });
        },

        closeIsolationForm() {
            this.formIsolationDialog = false;
            this.componentOverlay = false;
            this.deleteDialog = false;
            setTimeout(() => {
                this.formIsolationErrors = {
                    name: null,
                    user: { username: null, password: null },
                };
                this.editedIsolationInformation = Object.assign(
                    {},
                    this.defaultIsolationInformation
                );
                this.editedIsolationIndex = -1;
            }, 500);
        },

        //trigger everytime marker is drag
        changed(position) {
            this.editedIsolationInformation.latitude = position.latLng.lat();
            this.editedIsolationInformation.longitude = position.latLng.lng();
        },

        //Get Address to current location
        getUserGeolocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    this.setUserGeolocation
                );
            } else {
                window.clearInterval(window.locationInterval);
                alert("Geolocation is not supported by this browser.");
            }
        },

        //Set Address to current location
        setUserGeolocation(position) {
            var UserGeolocationLatitude = position.coords.latitude;
            var UserGeolocationLongitude = position.coords.longitude;
            this.center = {
                lat: UserGeolocationLatitude,
                lng: UserGeolocationLongitude,
            };
            this.address = this.center;
        },
    },
};
</script>
