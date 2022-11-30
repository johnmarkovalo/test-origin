<template>
    <v-navigation-drawer
        id="app-drawer"
        v-model="inputValue"
        app
        floating
        permanent
        mobile-breakpoint="991"
        width="260"
    >
        <v-list dense>
            <v-list-item
                v-for="(link, i) in links"
                :key="i"
                :to="link.to"
                active-class="primary"
                v-if="userPermission(link.module)"
                class="v-list-item"
            >
                <v-list-item-action>
                    <v-icon>{{ link.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-title v-text="link.text" />
            </v-list-item>
        </v-list>
        <template v-slot:append>
            <v-list dense>
                <v-list-item @click.stop="logout">
                    <v-list-item-action>
                        <v-icon>mdi-exit-to-app</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="subtitle-2"
                            >Logout</v-list-item-title
                        >
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </template>
    </v-navigation-drawer>
</template>

<script>
// Utilities
import { mapMutations, mapState } from "vuex";

export default {
    props: {
        opened: {
            type: Boolean,
            default: false,
        },
    },
    data: () => ({
        logo: "favicon.ico",
        links: [
            {
                to: "/admin/dashboard",
                icon: "mdi-view-dashboard",
                text: "Dashboard",
                module: "adminDashboard",
            },
            {
                to: "/admin/hospitals",
                icon: "mdi-hospital-building",
                text: "Hospitals",
                module: "adminHospital",
            },
            {
                to: "/admin/isolations",
                icon: "mdi-home-group",
                text: "Isolations",
                module: "adminIsolation",
            },
            {
                to: "/admin/users",
                icon: "mdi-account-circle",
                text: "Occupants",
                module: "adminUser",
            },
            {
                to: "/admin/room-requests",
                icon: "mdi-bed",
                text: "Hospital Room Requests",
                module: "adminRequest",
            },
            {
                to: "/admin/isolation-room-requests",
                icon: "mdi-home",
                text: "Isolation Room Requests",
                module: "adminIsolationRequest",
            },
            //Hospital
            {
                to: "/hospital/dashboard",
                icon: "mdi-view-dashboard",
                text: "Dashboard",
                module: "hospitalDashboard",
            },
            {
                to: "/hospital/rooms",
                icon: "mdi-bed-empty",
                text: "Rooms",
                module: "hospitalRoom",
            },
            {
                to: "/hospital/users",
                icon: "mdi-account-circle",
                text: "Occupants",
                module: "hospitalUser",
            },
            {
                to: "/hospital/room-requests",
                icon: "mdi-bed",
                text: "Room Requests",
                module: "hospitalRequest",
            },
            //Isolation
            {
                to: "/isolation/dashboard",
                icon: "mdi-view-dashboard",
                text: "Dashboard",
                module: "isolationDashboard",
            },
            {
                to: "/isolation/rooms",
                icon: "mdi-bed-empty",
                text: "Rooms",
                module: "isolationRoom",
            },
            {
                to: "/isolation/users",
                icon: "mdi-account-circle",
                text: "Occupants",
                module: "isolationUser",
            },
            {
                to: "/isolation/room-requests",
                icon: "mdi-bed",
                text: "Room Requests",
                module: "isolationRequest",
            },
        ],
        userRole: sessionStorage.getItem("user-type"),
        // userRole: "HOSPITAL"
    }),
    computed: {
        ...mapState("app", ["color"]),
        inputValue: {
            get() {
                return this.$store.state.app.drawer;
            },
            set(val) {
                this.setDrawer(val);
            },
        },
        items() {
            return this.$t("Layout.View.items");
        },
    },

    methods: {
        ...mapMutations("app", ["setDrawer", "toggleDrawer"]),
        userPermission(module) {
            var modules = {
                adminDashboard: true,
                adminHospital: true,
                adminIsolation: true,
                adminUser: true,
                adminRequest: true,
                adminIsolationRequest: true,
                // adminRoom: true,
                //hospital
                // hospitalDashboard: true,
                hospitalRoom: true,
                hospitalUser: true,
                hospitalRequest: true,

                isolationRoom: true,
                isolationUser: true,
                isolationRequest: true,
            };
            var permissions = {
                ADMINISTRATOR: {
                    ...modules,
                    hospitalDashboard: false,
                    hospitalRequest: false,
                    hospitalRoom: false,
                    hospitalUser: false,
                    isolationDashboard: false,
                    isolationRequest: false,
                    isolationRoom: false,
                    isolationUser: false,
                },
                HOSPITAL: {
                    ...modules,
                    adminRequest: false,
                    adminDashboard: false,
                    adminHospital: false,
                    adminUser: false,
                    adminRoom: false,

                    isolationRoom: false,
                    isolationUser: false,
                    isolationRequest: false,
                },
                ISOLATION: {
                    ...modules,
                    adminRequest: false,
                    adminDashboard: false,
                    adminHospital: false,
                    adminUser: false,
                    adminRoom: false,

                    hospitalRoom: false,
                    hospitalUser: false,
                    hospitalRequest: false,
                },
            };
            return permissions[this.userRole][module];
        },

        logout() {
            axios
                .get("/api/v1/logout")
                .then((response) => {
                    if (response.data.errors) {
                        this.error = response.data.errors;
                        return;
                    }
                    sessionStorage.clear();
                    this.$router.push("/login");
                })
                .catch((error) => {
                    if (error.response.data.message == "Unauthenticated.") {
                        sessionStorage.clear();
                        this.$router.push("/login");
                    }
                });
        },
    },
};
</script>

<style lang="scss">
#app-drawer {
    .v-list__tile {
        border-radius: 4px;

        &--buy {
            margin-top: auto;
            margin-bottom: 17px;
        }
    }

    .v-list .v-list-item--active {
        color: white !important;
    }
}
</style>
