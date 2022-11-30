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
                            loading-text="Fetching room list... Please wait"
                            :headers="tableRoomHeaders"
                            :items="tableRooms"
                            :search="tableSearch"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title class="headline"
                                        >Rooms</v-toolbar-title
                                    >
                                    <div class="flex-grow-1"></div>
                                    <v-btn
                                        small
                                        @click="formRoomDialog = true"
                                        color="primary"
                                    >
                                        <v-icon small left
                                            >mdi-plus-circle</v-icon
                                        >
                                        Add Room
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.id="{ item }">
                                {{
                                    tableRooms
                                        .map(function (x) {
                                            return x.id;
                                        })
                                        .indexOf(item.id) + 1
                                }}
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="editRoom(item)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon @click="deleteRoom(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="formRoomDialog" max-width="500px" persistent>
            <v-card>
                <v-overlay :value="componentOverlay">
                    <v-progress-circular
                        :size="100"
                        :width="5"
                        indeterminate
                    ></v-progress-circular>
                </v-overlay>
                <v-card-title class="headline">
                    {{ formRoomTitle }}
                </v-card-title>
                <v-card-text>
                    <v-row justify="center">
                        <v-col cols="12" md="6">
                            <v-text-field
                                type="text"
                                :error-messages="formRoomErrors.room_no"
                                v-model="editedRoomInformation.room_no"
                                label="Room Number"
                            />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                :items="items"
                                type="text"
                                :error-messages="formRoomErrors.status"
                                v-model="editedRoomInformation.status"
                                label="Status"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="px-12" text @click="closeRoomForm"
                        >Cancel</v-btn
                    >
                    <v-btn class="px-12" @click="saveRoom" color="primary"
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
            userId: sessionStorage.getItem("user-id"),
            profileId: sessionStorage.getItem("profile-id"),
            tableLoading: true,
            tableSearch: null,
            searchInput: "",
            //Room
            tableRooms: [],
            formRoomDialog: false,
            formRoomListDialog: false,

            formRoomErrors: {
                room_no: null,
            },

            tableRoomHeaders: [
                { text: "ID", value: "id" },
                { text: "Room Number", value: "room_no" },
                { text: "Status", value: "status" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    align: "center",
                },
            ],

            editedRoomIndex: -1,
            editedRoomInformation: {
                room_no: null,
            },

            items: ["OCCUPIED", "VACANT"],

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
                    (v) => !!v || "Room Number is required",
                    (v) =>
                        (!!v && v.length >= 10) ||
                        "Room Number must be 11 characters",
                    (v) =>
                        (!!v && v.length < 11) ||
                        "Room Number must not be more than 11 characters",
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
        formRoomTitle() {
            return this.editedRoomIndex === -1 ? "New Room" : "Edit Room";
        },
    },

    mounted() {
        this.fetchRooms();
    },

    methods: {
        //Room
        fetchRooms() {
            this.tableLoading = true;
            this.componentOverlay = true;
            axios
                .get("/api/v1/isolationrooms")
                .then((response) => {
                    this.tableRooms = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                    this.tableLoading = false;
                });
        },

        saveRoom() {
            this.componentOverlay = true;
            if (this.editedRoomIndex > -1) {
                this.updateRoom();
            } else {
                this.createRoom();
            }
        },

        createRoom() {
            axios
                .post("/api/v1/isolationrooms", {
                    ...this.editedRoomInformation,
                    isolation_id: this.profileId,
                })
                .then((response) => {
                    this.fetchRooms();
                    this.closeRoomForm();
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
                        this.formRoomErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        editRoom(contact) {
            this.editedRoomIndex = this.tableRooms.indexOf(contact);
            this.editedRoomInformation = Object.assign({}, contact);
            this.formRoomDialog = true;
        },

        updateRoom() {
            axios
                .put(
                    "/api/v1/isolationrooms/" + this.editedRoomInformation.id,
                    {
                        ...this.editedRoomInformation,
                        isolation_id: this.profileId,
                    }
                )
                .then((response) => {
                    this.fetchRooms();
                    this.closeRoomForm();
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
                        this.formRoomErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        deleteRoom(room) {
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
                            .delete("/api/v1/isolationrooms/" + room.id)
                            .then(() => {
                                this.fetchRooms();
                                this.closeRoomForm();
                                swal.fire(
                                    "Deleted!",
                                    "Room has been deleted.",
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

        closeRoomForm() {
            this.formRoomDialog = false;
            this.componentOverlay = false;
            this.deleteDialog = false;
            setTimeout(() => {
                this.formRoomErrors = {
                    room_no: null,
                };
                this.editedRoomInformation = Object.assign(
                    {},
                    this.defaultRoomInformation
                );
                this.editedRoomIndex = -1;
            }, 500);
        },
    },
};
</script>
