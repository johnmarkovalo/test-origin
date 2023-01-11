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
                            :items="filteredOccupants"
                            :search="tableSearch"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title class="headline"
                                        >Occupants</v-toolbar-title
                                    >
                                    <div class="flex-grow-1"></div>
                                    <v-checkbox
                                        v-model="discharged"
                                        label="Discharged"
                                    ></v-checkbox>
                                    <div class="flex-grow-1"></div>
                                    <v-checkbox
                                        v-model="covid"
                                        label="Positive"
                                    ></v-checkbox>
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
                                <v-btn icon @click="viewOccupant(item)">
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
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
                        <v-col cols="12">
                            <v-row justify="center">
                                <v-col cols="12" md="3">
                                    <v-text-field
                                        type="text"
                                        :error-messages="
                                            formOccupantErrors.name
                                        "
                                        v-model="editedOccupantInformation.name"
                                        label="Name"
                                    />
                                </v-col>
                                <v-col cols="12" md="3">
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
                                <v-col cols="12" md="3">
                                    <v-select
                                        :items="['MALE', 'FEMALE']"
                                        :error-messages="
                                            formOccupantErrors.gender
                                        "
                                        v-model="
                                            editedOccupantInformation.gender
                                        "
                                        label="Number"
                                    />
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-dialog
                                        ref="birthdateDialog"
                                        v-model="birthdateDialog"
                                        :return-value.sync="
                                            editedOccupantInformation.birthdate
                                        "
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="
                                                    editedOccupantInformation.birthdate
                                                "
                                                label="Birthday"
                                                readonly
                                                v-on="on"
                                            />
                                        </template>
                                        <v-date-picker
                                            v-model="
                                                editedOccupantInformation.birthdate
                                            "
                                            color="primary"
                                            scrollable
                                            dark
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="birthdateDialog = false"
                                                >Cancel</v-btn
                                            >
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="
                                                    $refs.birthdateDialog.save(
                                                        editedOccupantInformation.birthdate
                                                    )
                                                "
                                                >OK</v-btn
                                            >
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        type="text"
                                        :error-messages="
                                            formOccupantErrors.username
                                        "
                                        v-model="
                                            editedOccupantInformation.username
                                        "
                                        label="Username"
                                    />
                                </v-col>
                                <v-col cols="12" md="6"
                                    ><v-text-field
                                        v-model="
                                            editedOccupantInformation.password
                                        "
                                        label="Password"
                                        id="password"
                                        name="password"
                                        :append-icon="
                                            visible ? 'mdi-eye-off' : 'mdi-eye'
                                        "
                                        @click:append="visible = !visible"
                                        :rules="rules.passwordRules"
                                        :type="visible ? 'text' : 'password'"
                                    ></v-text-field
                                ></v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-col cols="12" md="4">
                                    <v-select
                                        :items="statuses"
                                        :error-messages="
                                            formOccupantErrors.status
                                        "
                                        v-model="
                                            editedOccupantInformation.status
                                        "
                                        label="Admission Status"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        :items="[
                                            'VACCINATED',
                                            'NON_VACCINATED',
                                        ]"
                                        :error-messages="
                                            formOccupantErrors.vaccination
                                        "
                                        v-model="
                                            editedOccupantInformation.vaccination
                                        "
                                        label="Vaccination Status"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
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
        <v-dialog v-model="occupantDetailsDialog" max-width="1000px" persistent>
            <v-card>
                <v-toolbar class="elevation-0">
                    <v-card-title class="headline">
                        Occupant Details</v-card-title
                    >
                    <v-spacer></v-spacer>
                    <v-btn icon @click="occupantDetailsDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-row justify="center">
                                <v-col cols="12" md="3">
                                    <v-text-field
                                        type="text"
                                        readonly
                                        :error-messages="
                                            formOccupantErrors.name
                                        "
                                        v-model="editedOccupantInformation.name"
                                        label="Name"
                                    />
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-text-field
                                        type="text"
                                        readonly
                                        :error-messages="
                                            formOccupantErrors.number
                                        "
                                        v-model="
                                            editedOccupantInformation.number
                                        "
                                        label="Number"
                                    />
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-select
                                        :items="['MALE', 'FEMALE']"
                                        readonly
                                        :error-messages="
                                            formOccupantErrors.gender
                                        "
                                        v-model="
                                            editedOccupantInformation.gender
                                        "
                                        label="Number"
                                    />
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-dialog
                                        ref="birthdateDialog"
                                        v-model="birthdateDialog"
                                        :return-value.sync="
                                            editedOccupantInformation.birthdate
                                        "
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="
                                                    editedOccupantInformation.birthdate
                                                "
                                                label="Birthday"
                                                readonly
                                                readonly
                                                v-on="on"
                                            />
                                        </template>
                                        <v-date-picker
                                            v-model="
                                                editedOccupantInformation.birthdate
                                            "
                                            color="primary"
                                            scrollable
                                            dark
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="birthdateDialog = false"
                                                >Cancel</v-btn
                                            >
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="
                                                    $refs.birthdateDialog.save(
                                                        editedOccupantInformation.birthdate
                                                    )
                                                "
                                                >OK</v-btn
                                            >
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-col cols="12" md="4">
                                    <v-select
                                        :items="statuses"
                                        readonly
                                        :error-messages="
                                            formOccupantErrors.status
                                        "
                                        v-model="
                                            editedOccupantInformation.status
                                        "
                                        label="Admission Status"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        :items="[
                                            'VACCINATED',
                                            'NON_VACCINATED',
                                        ]"
                                        readonly
                                        :error-messages="
                                            formOccupantErrors.vaccination
                                        "
                                        v-model="
                                            editedOccupantInformation.vaccination
                                        "
                                        label="Vaccination Status"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        :items="types"
                                        readonly
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
                                        readonly
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
                            <v-card class="elevation-0">
                                <v-toolbar class="elevation-0 mb-0">
                                    <v-toolbar-title class="title"
                                        >Patient Logs</v-toolbar-title
                                    >
                                    <v-spacer />
                                    <v-btn
                                        small
                                        color="primary"
                                        @click="formHistoryDialog = true"
                                    >
                                        <v-icon>mdi-plus</v-icon>
                                        Add Logs
                                    </v-btn>
                                </v-toolbar>
                                <v-card-text class="mt-0">
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            md="6"
                                            v-for="(
                                                log, index
                                            ) in editedOccupantInformation.occupant_history"
                                            :key="index"
                                        >
                                            <v-card>
                                                <v-card-text>
                                                    <v-row>
                                                        <v-col cols="10"
                                                            ><p>
                                                                Details Logs:
                                                                <strong>{{
                                                                    log.details
                                                                }}</strong>
                                                            </p>
                                                            <p>
                                                                Date and Time:
                                                                <strong
                                                                    >{{
                                                                        log.date
                                                                            | myDate
                                                                    }}
                                                                    {{
                                                                        log.time
                                                                            | myTime
                                                                    }}</strong
                                                                >
                                                            </p>
                                                            <p>
                                                                Activity By:
                                                                <strong>{{
                                                                    log.activity_by
                                                                }}</strong>
                                                            </p></v-col
                                                        >
                                                        <v-col cols="2">
                                                            <v-btn
                                                                icon
                                                                color="warning"
                                                                @click="
                                                                    editHistory(
                                                                        log
                                                                    )
                                                                "
                                                            >
                                                                <v-icon
                                                                    >mdi-pen
                                                                </v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                icon
                                                                color="error"
                                                                @click="
                                                                    deleteHistory(
                                                                        log
                                                                    )
                                                                "
                                                            >
                                                                <v-icon
                                                                    >mdi-delete
                                                                </v-icon>
                                                            </v-btn>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <p>
                                                                Remarks:
                                                                <strong>{{
                                                                    log.remarks
                                                                }}</strong>
                                                            </p>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <p>
                                                                Additional
                                                                Remarks:
                                                                <strong>{{
                                                                    log.additional_remarks
                                                                }}</strong>
                                                            </p>
                                                        </v-col>
                                                    </v-row>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="formHistoryDialog" max-width="500px" persistent>
            <v-card>
                <v-card-title class="headline"> Details </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-row justify="center">
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="
                                            editedHistoryInformation.details
                                        "
                                        label="Details"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="
                                            editedHistoryInformation.activity_by
                                        "
                                        label="Activity By"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-dialog
                                        ref="dateDialog"
                                        v-model="dateDialog"
                                        :return-value.sync="
                                            editedHistoryInformation.date
                                        "
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="
                                                    editedHistoryInformation.date
                                                "
                                                label="Date"
                                                readonly
                                                v-on="on"
                                            />
                                        </template>
                                        <v-date-picker
                                            v-model="
                                                editedHistoryInformation.date
                                            "
                                            color="primary"
                                            scrollable
                                            dark
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="dateDialog = false"
                                                >Cancel</v-btn
                                            >
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="
                                                    $refs.dateDialog.save(
                                                        editedHistoryInformation.date
                                                    )
                                                "
                                                >OK</v-btn
                                            >
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-dialog
                                        ref="timeDialog"
                                        v-model="timeDialog"
                                        :return-value.sync="
                                            editedHistoryInformation.time
                                        "
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="
                                                    editedHistoryInformation.time
                                                "
                                                label="Birthday"
                                                readonly
                                                v-on="on"
                                            />
                                        </template>
                                        <v-time-picker
                                            v-model="
                                                editedHistoryInformation.time
                                            "
                                            color="primary"
                                            scrollable
                                            dark
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="timeDialog = false"
                                                >Cancel</v-btn
                                            >
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="
                                                    $refs.timeDialog.save(
                                                        editedHistoryInformation.time
                                                    )
                                                "
                                                >OK</v-btn
                                            >
                                        </v-time-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea
                                        v-model="
                                            editedHistoryInformation.remarks
                                        "
                                        label="Remarks"
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea
                                        v-model="
                                            editedHistoryInformation.additional_remarks
                                        "
                                        label="Additional Remarks"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="px-12" text @click="closeHistoryForm"
                        >Cancel</v-btn
                    >
                    <v-btn class="px-12" @click="saveHistory" color="primary"
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
            visible: false,
            componentOverlay: false,
            // profileId: sessionStorage.getItem("profile-id"),
            tableLoading: true,
            tableSearch: null,
            searchInput: "",
            visible: false,
            birthdateDialog: false,
            dateDialog: false,
            timeDialog: false,
            //filter
            discharged: false,
            covid: false,
            //Occupant
            tableOccupants: [],
            formOccupantDialog: false,
            formHistoryDialog: false,
            occupantDetailsDialog: false,
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
                { text: "Gender", value: "gender" },
                { text: "Address", value: "address" },
                { text: "Contract", value: "number" },
                { text: "Admission Status", value: "status" },
                { text: "Vaccination Status", value: "vaccination" },
                { text: "Covid Status", value: "type" },
                { text: "Birthday", value: "birthdate" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    align: "center",
                },
            ],

            editedOccupantIndex: -1,
            editedHistoryIndex: -1,
            editedOccupantInformation: {
                name: null,
                address: null,
                number: null,
                lat: 6.9214,
                lng: 122.079,
                birthdate: new Date().toISOString().substr(0, 10),
            },
            editedHistoryInformation: {
                detail: null,
                activity_by: null,
                date: new Date().toISOString().substr(0, 10),
                time: new Date().toISOString().substr(11, 5),
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

        filteredOccupants() {
            let filteredOccupants = this.tableOccupants;
            if (this.discharged) {
                filteredOccupants = filteredOccupants.filter((occupant) => {
                    return occupant.status == "DISCHARGED";
                });
            }
            if (this.covid) {
                filteredOccupants = filteredOccupants.filter((occupant) => {
                    return occupant.type == "COVID";
                });
            }

            return filteredOccupants;
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
                    password_confirmation:
                        this.editedOccupantInformation.password,
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

        viewOccupant(occupant) {
            this.editedOccupantInformation = Object.assign({}, occupant);
            this.occupantDetailsDialog = true;
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

        editHistory(history) {
            this.editedHistoryIndex =
                this.editedOccupantInformation.occupant_history.indexOf(
                    history
                );
            this.editedHistoryInformation = Object.assign({}, history);
            this.formHistoryDialog = true;
        },

        saveHistory() {
            this.componentOverlay = true;
            if (this.editedHistoryIndex > -1) {
                this.updateHistory();
            } else {
                this.createHistory();
            }
        },

        createHistory() {
            axios
                .post(
                    "/api/v1/occupants/" +
                        this.editedOccupantInformation.id +
                        "/history",
                    {
                        ...this.editedHistoryInformation,
                    }
                )
                .then((response) => {
                    this.fetchOccupants();
                    this.closeHistoryForm();
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
                        this.formHistoryErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        updateHistory() {
            axios
                .put(
                    "/api/v1/occupants/" +
                        this.editedOccupantInformation.id +
                        "/history/" +
                        this.editedHistoryInformation.id,
                    {
                        ...this.editedHistoryInformation,
                    }
                )
                .then((response) => {
                    this.fetchOccupants();
                    this.closeHistoryForm();
                    this.editedOccupantInformation.occupant_history[
                        this.editedHistoryIndex
                    ] = this.editedHistoryInformation;
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
                        this.formHistoryErrors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {});
        },

        deleteHistory() {
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
                                "/api/v1/occupants/" +
                                    this.editedOccupantInformation.id +
                                    "/history/" +
                                    this.editedHistoryInformation.id
                            )
                            .then(() => {
                                this.fetchOccupants();
                                this.closeHistoryForm();
                                swal.fire(
                                    "Deleted!",
                                    "History has been deleted.",
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

        closeHistoryForm() {
            this.formHistoryDialog = false;
            this.componentOverlay = false;
            this.deleteDialog = false;
            setTimeout(() => {
                this.formHistoryErrors = {
                    name: null,
                };
                this.editedHistoryInformation = Object.assign(
                    {},
                    this.defaultHistoryInformation
                );
                this.editedHistoryIndex = -1;
            }, 500);
        },
    },
};
</script>
