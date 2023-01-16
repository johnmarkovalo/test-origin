<template>
    <v-container fill-height fluid grid-list-xl>
        <v-row justify="center">
            <v-col cols="12" md="10" class="dashboard-stats">
                <v-row class="mt-4">
                    <v-col>
                        <h1>Dashboard</h1>
                        <h4 class="mt-4">Quick Stats</h4>
                    </v-col>
                    <v-col class="text-right">
                        <export-excel
                            class="btn btn-default"
                            :data="json_data"
                            :fields="json_fields"
                            worksheet="Report"
                            :name="fileName()"
                        >
                                  <v-btn color="primary" small
                                >Download Report</v-btn
                            >
                             
                        </export-excel>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                        sm="6"
                        xs="12"
                        md="6"
                        lg="3"
                        xl="2"
                        v-for="(stat, index) in stats"
                        :key="index"
                    >
                        <material-stats-card
                            :color="stat.color"
                            :icon="stat.icon"
                            :title="stat.title"
                            :value="formattedAmount(stat.value)"
                            :small-value="stat.smallvalue"
                            :sub-icon="stat.subicon"
                            :sub-icon-color="stat.subiconcolor"
                            :sub-text="stat.subtext"
                            :sub-text-color="stat.subtextcolor"
                        />
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            stats: [
                {
                    color: "green",
                    icon: "mdi-account-group",
                    title: "Occupants",
                    value: "0",
                    smallvalue: "",
                    subicon: "mdi-update",
                    subiconcolor: "",
                    subtext: "Just Updated",
                    subtextcolor: "",
                },
                {
                    color: "orange",
                    icon: "fa-user-check",
                    title: "Discharged",
                    value: "0",
                    smallvalue: "",
                    subicon: "mdi-update",
                    subiconcolor: "",
                    subtext: "Just Updated",
                    subtextcolor: "",
                },
                {
                    color: "info",
                    icon: "fa-syringe",
                    title: "Vaccinated",
                    value: "0",
                    smallvalue: "",
                    subicon: "mdi-update",
                    subiconcolor: "",
                    subtext: "Just Updated",
                    subtextcolor: "",
                },
                {
                    color: "pink",
                    icon: "fa-virus",
                    title: "Positive",
                    value: "0",
                    smallvalue: "",
                    subicon: "mdi-update",
                    subiconcolor: "",
                    subtext: "Just Updated",
                    subtextcolor: "",
                },
                {
                    color: "red",
                    icon: "fa-cross",
                    title: "Mortality",
                    value: "0",
                    smallvalue: "",
                    subicon: "mdi-update",
                    subiconcolor: "",
                    subtext: "Just Updated",
                    subtextcolor: "",
                },
            ],
            profileId: sessionStorage.getItem("profile-id"),
            componentOverlay: false,
            json_fields: {
                Occupants: "occupants",
                Discharged: "discharged",
                Vaccinated: "vaccinated",
                Positive: "positive",
                Mortality: "deceased",
            },
            json_data: [
                {
                    occupants: "0",
                    discharged: "0",
                    vaccinated: "0",
                    positive: "0",
                    deceased: "0",
                },
            ],
            json_meta: [
                [
                    {
                        key: "charset",
                        value: "utf-8",
                    },
                ],
            ],
            date: new Date().toISOString().substr(0, 10),
        };
    },

    methods: {
        fetchDashboard() {
            axios
                .get("/api/v1/dashboard")
                .then((response) => {
                    let stats = response.data;
                    this.json_data = [stats];
                    let i = 0;
                    let keys = [
                        "occupants",
                        "discharged",
                        "vaccinated",
                        "positive",
                        "deceased",
                    ];
                    this.stats.forEach((stat) => {
                        stat.value = stats[keys[i]].toString();
                        i++;
                    });
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.componentOverlay = false;
                });
        },

        formattedAmount(number) {
            let internationalNumberFormat = new Intl.NumberFormat("en-US");
            return internationalNumberFormat.format(number);
        },

        fileName() {
            //With readable date format
            return "Dashboard Report as Of " + this.date;
        },
    },

    mounted() {
        this.fetchDashboard();
    },
};
</script>
