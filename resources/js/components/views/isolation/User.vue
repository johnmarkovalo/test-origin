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
                            loading-text="Fetching occupants list... Please wait"
                            :headers="tableOccupantHeaders"
                            :items="tableOccupants"
                            :search="tableSearch"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title class="headline"
                                        >Occupants</v-toolbar-title
                                    >
                                    <div class="flex-grow-1"></div>
                                    <v-btn
                                        small
                                        @click="formOccupantDialog = true"
                                        color="primary"
                                    >
                                        <v-icon small left
                                            >mdi-plus-circle</v-icon
                                        >
                                        Add Occupant
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.id="{ item }">
                                {{
                                    tableOccupants
                                        .map(function (x) {
                                            return x.id;
                                        })
                                        .indexOf(item.id) + 1
                                }}
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="editOccupant(item)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon @click="deleteOccupant(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="formOccupantDialog" max-width="1000px" persistent>
            <v-card>
                <v-overlay :value="componentOverlay">
                    <v-progress-circular
                        :size="100"
                        :width="5"
                        indeterminate
                    ></v-progress-circular>
                </v-overlay>
                <v-card-title class="headline">
                    {{ formOccupantTitle }}
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-row justify="center">
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        type="text"
                                        :error-messages="
                                            formOccupantErrors.name
                                        "
                                        v-model="editedOccupantInformation.name"
                                        label="Name"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        type="text"
                                        :error-messages="
                                            formOccupantErrors.number
                                        "
                                        v-model="
                                            editedOccupantInformation.number
                                        "
                                        label="Number"
                                    />
                                </v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-col cols="12" md="6">
                                    <v-select
                                        :items="statuses"
                                        :error-messages="
                                            formOccupantErrors.status
                                        "
                                        v-model="
                                            editedOccupantInformation.status
                                        "
                                        label="Status"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-select
                                        :items="types"
                                        :error-messages="
                                            formOccupantErrors.type
                                        "
                                        v-model="editedOccupantInformation.type"
                                        label="Type"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        type="text"
                                        :error-messages="
                                            formOccupantErrors.address
                                        "
                                        v-model="
                                            editedOccupantInformation.address
                                        "
                                        label="Address"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="px-12" text @click="closeOccupantForm"
                        >Cancel</v-btn
                    >
                    <v-btn class="px-12" @click="saveOccupant" color="primary"
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
            searchInput: "",
            visible: false,
            //Occupant
            tableOccupants: [],
            formOccupantDialog: false,
            formOccupantListDialog: false,
            statuses: ["ADMITTED", "DISCHARGED"],
            types: ["COVID", "NON_COVID"],

            formOccupantErrors: {
                name: null,
                address: null,
                number: null,
                lat: 6.9214,
                lng: 122.079,
            },

            tableOccupantHeaders: [
                { text: "ID", value: "id" },
                { text: "Name", value: "name" },
                { text: "Address", value: "address" },
                { text: "Contract", value: "number" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    align: "center",
                },
            ],

            editedOccupantIndex: -1,
            editedOccupantInformation: {
                name: null,
                address: null,
                number: null,
                lat: 6.9214,
                lng: 122.079,
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
                occupantsRules: [
                    (v) => !!v || "Occupant Number is required",
                    (v) =>
                        (!!v && v.length >= 10) ||
                        "Occupant Number must be 11 characters",
                    (v) =>
                        (!!v && v.length < 11) ||
                        "Occupant Number must not be more than 11 characters",
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
        formOccupantTitle() {
            return this.editedOccupantIndex === -1
                ? "New Occupant"
                : "Edit Occupant";
        },
    },

    mounted() {
        this.fetchOccupants();
    },

    methods: {
        //Occupant
        fetchOccupants() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/occupants")
                .then((response) => {
                    this.tableOccupants = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        saveOccupant() {
            this.componentOverlay = true;
            if (this.editedOccupantIndex > -1) {
                this.updateOccupant();
            } else {
                this.createOccupant();
            }
        },

        createOccupant() {
            axios
                .post("/api/v1/occupants", {
                    ...this.editedOccupantInformation,
                })
                .then((response) => {
                    this.fetchOccupants();
                    this.closeOccupantForm();
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
                        this.formOccupantErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        editOccupant(occupants) {
            this.editedOccupantIndex = this.tableOccupants.indexOf(occupants);
            this.editedOccupantInformation = Object.assign({}, occupants);
            console.log(this.editedOccupantInformation.type);
            this.formOccupantDialog = true;
        },

        updateOccupant() {
            axios
                .put("/api/v1/occupants/" + this.editedOccupantInformation.id, {
                    ...this.editedOccupantInformation,
                })
                .then((response) => {
                    this.fetchOccupants();
                    this.closeOccupantForm();
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
                        this.formOccupantErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        deleteOccupant(occupants) {
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
                            .delete("/api/v1/occupants/" + occupants.id)
                            .then(() => {
                                this.fetchOccupants();
                                this.closeOccupantForm();
                                swal.fire(
                                    "Deleted!",
                                    "Occupant has been deleted.",
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

        closeOccupantForm() {
            this.formOccupantDialog = false;
            this.componentOverlay = false;
            this.deleteDialog = false;
            setTimeout(() => {
                this.formOccupantErrors = {
                    name: null,
                };
                this.editedOccupantInformation = Object.assign(
                    {},
                    this.defaultOccupantInformation
                );
                this.editedOccupantIndex = -1;
            }, 500);
        },
    },
};
</script>
