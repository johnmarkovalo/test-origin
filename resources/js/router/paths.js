import Landing from "../components/views/Landing.vue";
import Signin from "../components/views/Signin.vue";
import Signup from "../components/views/Signup.vue";

//Admin
import AdminScaffold from "../components/views/admin/Scaffold.vue";
import AdminDashboard from "../components/views/admin/Dashboard.vue";
import AdminHospitals from "../components/views/admin/Hospital.vue";
import AdminIsolations from "../components/views/admin/Isolation.vue";
import AdminRooms from "../components/views/admin/Room.vue";
import AdminUsers from "../components/views/admin/User.vue";
import AdminRequests from "../components/views/admin/Request.vue";
import AdminIsolationRequests from "../components/views/admin/IsolationRequest.vue";

//Hospital
import HospitalScaffold from "../components/views/hospital/Scaffold.vue";
import HospitalDashboard from "../components/views/hospital/Dashboard.vue";
import HospitalRooms from "../components/views/hospital/Room.vue";
import HospitalUsers from "../components/views/hospital/User.vue";
import HospitalRequests from "../components/views/hospital/Request.vue";

//Isolation
import IsolationScaffold from "../components/views/isolation/Scaffold.vue";
import IsolationDashboard from "../components/views/isolation/Dashboard.vue";
import IsolationRooms from "../components/views/isolation/Room.vue";
import IsolationUsers from "../components/views/isolation/User.vue";
import IsolationRequests from "../components/views/isolation/Request.vue";

// Pusher Component
import Pusher from "../components/views/Pusher.vue";

//Admin Routes
let adminRoutes = {
    path: "/admin",
    component: AdminScaffold,
    redirect: "/admin/hospitals",
    name: "Components",
    children: [
        // { path: "purchase-histories", name: "Purchase Histories", components: { default: PurchaseHistory }},
        {
            path: "/admin/dashboard",
            name: "Dashboard",
            components: { default: AdminDashboard },
        },
        {
            path: "/admin/hospitals",
            name: "Hospitals",
            components: { default: AdminHospitals },
        },
        {
            path: "/admin/isolations",
            name: "Isolations",
            components: { default: AdminIsolations },
        },
        {
            path: "/admin/hospital-rooms",
            name: "Rooms",
            components: { default: AdminRooms },
        },
        {
            path: "/admin/room-requests",
            name: "Requests",
            components: { default: AdminRequests },
        },
        {
            path: "/admin/isolation-room-requests",
            name: "Isolation Requests",
            components: { default: AdminIsolationRequests },
        },
        {
            path: "/admin/users",
            name: "Users",
            components: { default: AdminUsers },
        },
    ],
};

//Admin Routes
let hospitalRoutes = {
    path: "/hospital",
    component: AdminScaffold,
    redirect: "/hospital/dashboard",
    name: "Components",
    children: [
        // { path: "purchase-histories", name: "Purchase Histories", components: { default: PurchaseHistory }},
        {
            path: "/hospital/dashboard",
            name: "Dashboard",
            components: { default: HospitalDashboard },
        },
        {
            path: "/hospital/rooms",
            name: "Rooms",
            components: { default: HospitalRooms },
        },
        {
            path: "/hospital/users",
            name: "Users",
            components: { default: HospitalUsers },
        },
        {
            path: "/hospital/room-requests",
            name: "Requests",
            components: { default: HospitalRequests },
        },
    ],
};

//Admin Routes
let isolationRoutes = {
    path: "/isolation",
    component: AdminScaffold,
    redirect: "/isolation/dashboard",
    name: "Components",
    children: [
        // { path: "purchase-histories", name: "Purchase Histories", components: { default: PurchaseHistory }},
        {
            path: "/isolation/dashboard",
            name: "Dashboard",
            components: { default: IsolationDashboard },
        },
        {
            path: "/isolation/rooms",
            name: "Rooms",
            components: { default: IsolationRooms },
        },
        {
            path: "/isolation/users",
            name: "Users",
            components: { default: IsolationUsers },
        },
        {
            path: "/isolation/room-requests",
            name: "Requests",
            components: { default: IsolationRequests },
        },
    ],
};

const routes = [
    {
        path: "/",
        name: "Landing",
        component: Landing,
    },
    {
        path: "/login",
        name: "Signin",
        component: Signin,
    },
    {
        path: "/register",
        name: "Signup",
        component: Signup,
    },
    adminRoutes,
    hospitalRoutes,
    isolationRoutes,

    // Sample Route For Pusher
    {
        path: "/pusher",
        name: "Pusher",
        component: Pusher,
    },
];

export default routes;
