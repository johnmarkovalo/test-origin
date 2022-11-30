<template>
    <v-app>
        <views-navigation :color="color" :flat="flat" />
        <div>
            <!-- ======= Hero Section ======= -->
            <section class="hero-section" id="hero" v-if="!userId">
                <v-container>
                    <v-row justify="center" class="align-items-center">
                        <v-col cols="12" xl="8" class="hero-text-image">
                            <v-row>
                                <v-col
                                    cols="12"
                                    lg="6"
                                    class="text-center text-lg-left"
                                >
                                    <h1 data-aos="fade-right">
                                        HOSPITAL <strong>TRACKER</strong>
                                    </h1>
                                    <p
                                        class="mb-5"
                                        data-aos="fade-right"
                                        data-aos-delay="100"
                                    >
                                        Discover the closest hospital near you.
                                    </p>
                                    <!-- <v-btn data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500" color="primary" size="100px" rounded x-large class="btn-primary">START THE TEST</v-btn> -->
                                    <p
                                        data-aos="fade-right"
                                        data-aos-delay="200"
                                        data-aos-offset="-500"
                                    >
                                        <a
                                            class="btn btn-started"
                                            @click="$vuetify.goTo('#search')"
                                            >GET STARTED</a
                                        >
                                    </p>
                                </v-col>
                                <v-col
                                    cols="12"
                                    lg="6"
                                    class="hidden-md-and-down"
                                >
                                    <img
                                        src="svg/Hero.svg"
                                        alt="Image"
                                        data-aos="fade-right"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-container>
            </section>
            <!-- End Hero -->
            <!-- ======= Hero Section ======= -->
            <section class="search-section" id="search" v-else>
                <v-container>
                    <v-row justify="center">
                        <v-col cols="12">
                            <GmapMap
                                ref="map"
                                style="width: 100%; height: 800px"
                                :zoom="16"
                                :center="center"
                            >
                                <GmapMarker
                                    :position="address"
                                    icon="https://res.cloudinary.com/mactimestwo/image/upload/v1630609205/person_ssy7uc.ico"
                                />
                                <GmapMarker
                                    v-for="latlng in hospitalsLatLng"
                                    :key="latlng.id"
                                    :position="latlng"
                                    :clickable="true"
                                    @click="showHospital(latlng)"
                                    icon="https://res.cloudinary.com/mactimestwo/image/upload/v1630607746/marker_mqvzre.ico"
                                />

                                <GmapMarker
                                    v-for="latlng in isolationsLatLng"
                                    :key="latlng.id"
                                    :position="latlng"
                                    :clickable="true"
                                    @click="showHospital(latlng)"
                                    icon="https://res.cloudinary.com/mactimestwo/image/upload/v1669825557/isolation_hkfz2q.ico"
                                />
                            </GmapMap>
                        </v-col>
                    </v-row>
                </v-container>
            </section>
            <!-- End Hero -->
        </div>
        <v-dialog v-model="hospitalDialog" max-width="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Hospital/Isolation Information</span>
                    <v-spacer />
                    <v-icon
                        large
                        color="primary"
                        @click="hospitalDialog = false"
                        >mdi-times</v-icon
                    >
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>Name:</v-col>
                            <v-col class="text-left">{{ hospital.name }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col>Address:</v-col>
                            <v-col class="text-left">{{
                                hospital.address
                            }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col>Contact:</v-col>
                            <v-col class="text-left">{{
                                hospital.number
                            }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col>Status:</v-col>
                            <v-col class="text-left"
                                ><v-chip :color="chipColor(hospital.status)">{{
                                    hospital.status
                                }}</v-chip></v-col
                            >
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-scale-transition>
            <v-btn
                fab
                v-show="fab"
                v-scroll="onScroll"
                dark
                fixed
                bottom
                right
                color="secondary"
                @click="toTop"
            >
                <v-icon>mdi-arrow-up</v-icon>
            </v-btn>
        </v-scale-transition>
    </v-app>
</template>

<script>
export default {
    name: "app",
    data() {
        return {
            fab: null,
            userId: sessionStorage.getItem("user-id"),
            color: "",
            flat: null,
            hospital: { id: 1025167, lat: 6.9214, lng: 122.075 },
            hospitals: [],
            hospitalsLatLng: [],
            isolationsLatLng: [],
            //Google Maps Variables
            center: { lat: 6.9214, lng: 122.079 },
            address: { lat: 6.9214, lng: 122.079 },
            //Modals
            hospitalDialog: false,
        };
    },

    methods: {
        onScroll(e) {
            if (typeof window === "undefined") return;
            const top = window.pageYOffset || e.target.scrollTop || 0;
            this.fab = top > 60;
        },

        toTop() {
            this.$vuetify.goTo(0);
        },

        fetchHospitals() {
            this.hospitalsLatLng = [];
            this.isolationsLatLng = [];
            axios
                .get("/api/v1/nearbyhospitals", {
                    params: {
                        lat: this.center.lat,
                        lng: this.center.lng,
                    },
                })
                .then((response) => {
                    this.hospitals = response.data.nearby;
                    let hospitals = response.data.nearby;
                    hospitals.forEach((hospital) => {
                        var position = {
                            id: hospital.id,
                            lat: hospital.latitude,
                            type: hospital.type,
                            lng: hospital.longitude,
                        };
                        if (hospital.type == "HOSPITAL")
                            this.hospitalsLatLng.push(position);
                        else this.isolationsLatLng.push(position);
                    });
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.tableLoading = false;
                    this.componentOverlay = false;
                });
        },

        showHospital(hospital) {
            this.hospital = this.hospitals.find((x) => x.id === hospital.id);
            this.hospitalDialog = true;
            this.getDirection(this.center, hospital);
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

        chipColor(status) {
            if (status == "RECIEVING") {
                return "success";
            } else {
                return "red";
            }
        },

        //Get Direction from user to hospital
        getDirection(user, hospital) {
            this.$gmapApiPromiseLazy().then(() => {
                this.$options.directionsDisplay.set("directions", null);
                this.$options.directionsService.route(
                    {
                        origin: user,
                        destination: hospital,
                        travelMode: "DRIVING",
                    },
                    (result, status) => {
                        if (status === "OK") {
                            this.$options.directionsDisplay.setDirections(
                                result
                            );
                        }
                    }
                );
            });
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
        this.getUserGeolocation();
        this.fetchHospitals();
    },

    mounted() {
        // this.pusherInit();
        //Routes
        this.$nextTick(function () {
            this.$gmapApiPromiseLazy().then(() => {
                this.$options.directionsService =
                    new google.maps.DirectionsService();
                this.$options.directionsDisplay =
                    new google.maps.DirectionsRenderer();
                this.$options.directionsDisplay.setMap(
                    this.$refs.map.$mapObject
                );
            });
        });
    },

    beforeRouteEnter(to, from, next) {
        // if (sessionStorage.getItem("user-type")) {
        //     if (sessionStorage.getItem("user-type") == "ADMINISTRATOR") {
        //         return next("admin/dashboard");
        //     } else if (sessionStorage.getItem("user-type") == "SUBSCRIBER") {
        //         return next("/dashboard");
        //     }
        // }
        next();
    },
};
</script>
