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
                            :headers="tableRequestHeaders"
                            :items="tableRequests"
                            :search="tableSearch"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title class="headline"
                                        >Requests</v-toolbar-title
                                    >
                                    <div class="flex-grow-1"></div>
                                    <v-btn
                                        small
                                        @click="formRequestDialog = true"
                                        color="primary"
                                    >
                                        <v-icon small left
                                            >mdi-plus-circle</v-icon
                                        >
                                        Add Request
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.id="{ item }">
                                {{
                                    tableRequests
                                        .map(function (x) {
                                            return x.id;
                                        })
                                        .indexOf(item.id) + 1
                                }}
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="editRequest(item)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon @click="deleteRequest(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="formRequestDialog" max-width="800px" persistent>
            <v-card>
                <v-overlay :value="componentOverlay">
                    <v-progress-circular
                        :size="100"
                        :width="5"
                        indeterminate
                    ></v-progress-circular>
                </v-overlay>
                <v-card-title class="headline">
                    {{ formRequestTitle }}
                </v-card-title>
                <v-card-text>
                    <v-row justify="center">
                        <v-col cols="12" md="6">
                            <v-select
                                :items="occupants"
                                item-text="name"
                                item-value="id"
                                :error-messages="formRequestErrors.occupant_id"
                                v-model="editedRequestInformation.occupant_id"
                                @change="occupantChange()"
                                label="Occupant"
                            />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                :items="rooms"
                                item-text="room_no"
                                item-value="id"
                                :error-messages="
                                    formRequestErrors.isolation_room_id
                                "
                                v-model="
                                    editedRequestInformation.isolation_room_id
                                "
                                label="Room"
                            />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                :items="statuses"
                                :error-messages="formRequestErrors.status"
                                v-model="editedRequestInformation.status"
                                label="Status"
                            />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                :items="types"
                                :error-messages="formRequestErrors.type"
                                v-model="editedRequestInformation.type"
                                label="Types"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="px-12" text @click="closeRequestForm"
                        >Cancel</v-btn
                    >
                    <v-btn class="px-12" @click="saveRequest" color="primary"
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
            //Request
            tableRequests: [],
            formRequestDialog: false,
            formRequestListDialog: false,

            formRequestErrors: {
                name: null,
                address: null,
                number: null,
                lat: 6.9214,
                lng: 122.079,
            },

            tableRequestHeaders: [
                { text: "ID", value: "id" },
                { text: "Name", value: "occupant.name" },
                { text: "Address", value: "occupant.address" },
                { text: "Contract", value: "occupant.number" },
                { text: "Room", value: "isolation_room.room_no" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    align: "center",
                },
            ],

            editedRequestIndex: -1,
            editedRequestInformation: {
                name: null,
                address: null,
                number: null,
                latitude: 6.9214,
                longitude: 122.079,
            },

            rooms: [],
            occupants: [],
            statuses: ["ADMITTED", "DISCHARGED"],
            types: ["COVID", "NON_COVID"],

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
                    (v) => !!v || "Request Number is required",
                    (v) =>
                        (!!v && v.length >= 10) ||
                        "Request Number must be 11 characters",
                    (v) =>
                        (!!v && v.length < 11) ||
                        "Request Number must not be more than 11 characters",
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
        formRequestTitle() {
            return this.editedRequestIndex === -1
                ? "New Request"
                : "Edit Request";
        },
    },

    mounted() {
        this.fetchRequests();
        this.fetchRooms();
        this.fetchOccupants();
    },

    methods: {
        //Request
        fetchRequests() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/isolationroomrequests")
                .then((response) => {
                    this.tableRequests = response.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        //Room
        fetchRooms() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/isolationrooms")
                .then((response) => {
                    this.rooms = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        //Occupant
        fetchOccupants() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/occupants")
                .then((response) => {
                    this.occupants = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        saveRequest() {
            this.componentOverlay = true;
            if (this.editedRequestIndex > -1) {
                this.updateRequest();
            } else {
                this.createRequest();
            }
        },

        createRequest() {
            axios
                .post("/api/v1/isolationroomrequests", {
                    ...this.editedRequestInformation,
                })
                .then((response) => {
                    this.fetchRequests();
                    this.closeRequestForm();
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
                        this.formRequestErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        editRequest(isolation) {
            this.editedRequestIndex = this.tableRequests.indexOf(isolation);
            this.editedRequestInformation = Object.assign({}, isolation);
            this.formRequestDialog = true;
        },

        updateRequest() {
            axios
                .put(
                    "/api/v1/isolationroomrequests/" +
                        this.editedRequestInformation.id,
                    {
                        ...this.editedRequestInformation,
                    }
                )
                .then((response) => {
                    this.fetchRequests();
                    this.closeRequestForm();
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
                        this.formRequestErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        deleteRequest(isolation) {
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
                            .delete(
                                "/api/v1/isolationroomrequests/" + isolation.id
                            )
                            .then(() => {
                                this.fetchRequests();
                                this.closeRequestForm();
                                swal.fire(
                                    "Deleted!",
                                    "Request has been deleted.",
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

        closeRequestForm() {
            this.formRequestDialog = false;
            this.componentOverlay = false;
            this.deleteDialog = false;
            setTimeout(() => {
                this.formRequestErrors = {
                    name: null,
                };
                this.editedRequestInformation = Object.assign(
                    {},
                    this.defaultRequestInformation
                );
                this.editedRequestIndex = -1;
            }, 500);
        },

        occupantChange() {
            let occupant = this.occupants.find(
                (x) => x.id == this.editedRequestInformation.occupant_id
            );
            console.log(this.editedRequestInformation);
            this.editedRequestInformation.status = occupant.status;
            this.editedRequestInformation.type = occupant.type;
        },
    },
};
</script>
