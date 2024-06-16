<template>

    <Head title="Welcome" />
    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-4 text-3xl font-bold">Property Listings</h1>

        <div class="flex flex-col mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center">
                <input type="text" v-model="searchTitle" placeholder="Search by title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md sm:w-auto sm:mr-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <input type="text" v-model="searchLocation" placeholder="Search by location"
                    class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md sm:w-auto sm:mt-0 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <select v-model="sortOption"
                    class="w-full px-8 py-2 mt-2 border border-gray-300 rounded-md sm:w-auto sm:mt-0 sm:ml-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Sort by</option>
                    <option value="priceAsc">Price (Low to High)</option>
                    <option value="priceDesc">Price (High to Low)</option>
                    <option value="dateAsc">Date Listed (Oldest First)</option>
                    <option value="dateDesc">Date Listed (Newest First)</option>
                </select>
            </div>
            <div class="flex items-center justify-between mt-8">
                <button @click="previousPage" :disabled="currentPage === 1"
                    class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <span class="mx-4">Page {{ currentPage }} of {{ totalPages }}</span>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="property in paginatedProperties" :key="property.id"
                class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img :src="property.photos.thumb" :alt="property.title" class="object-cover w-full h-48" />
                <div class="flex flex-col justify-between flex-grow p-4">
                    <div>
                        <h3 class="mb-2 text-xl font-semibold">{{ property.title }}</h3>
                        <p class="text-gray-500">Location: {{ property.geo.street }}</p>
                        <p class="mb-2 text-gray-500">{{ property.geo.province }}, {{ property.geo.country }}</p>
                        <p class="mb-4 text-gray-600">{{ property.description }}</p>
                    </div>
                    <div class="mt-auto">
                        <p class="mb-2 text-sm text-gray-500 uppercase">Listed on: {{ formatDate(property.date_listed)
                            }}</p>
                        <p class="text-2xl font-bold text-blue-600">{{ formatPrice(property.price,
                            property.currency_symbol) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { format } from 'date-fns';
import axios from 'axios';
import { API_BASE_URL } from '../../../config'; // Update the import path according to your project structure

export default {
    components: {
        Head
    },

    data() {
        return {
            properties: [],
            searchTitle: '',
            searchLocation: '',
            sortOption: '',
            currentPage: 1,
            perPage: 25,
            province: '',
            meta: {}, // Add the meta property to the component's data
        };
    },

    computed: {
        filteredProperties() {
            const titleQuery = this.searchTitle.toLowerCase();
            const locationQuery = this.searchLocation.toLowerCase();

            const titleMatchedProperties = this.properties.filter(property => {
                return property.title.toLowerCase().includes(titleQuery);
            });

            const locationMatchedProperties = this.properties.filter(property => {
                return property.geo.street.toLowerCase().includes(locationQuery);
            });

            const combinedProperties = [...new Set([...titleMatchedProperties, ...locationMatchedProperties])];

            return combinedProperties.filter(property => {
                return property.for_sale && !property.sold;
            });
        },

        sortedProperties() {
            return this.filteredProperties.sort((a, b) => {
                if (this.sortOption === 'priceAsc') {
                    return a.price - b.price;
                } else if (this.sortOption === 'priceDesc') {
                    return b.price - a.price;
                } else if (this.sortOption === 'dateAsc') {
                    return new Date(a.date_listed) - new Date(b.date_listed);
                } else if (this.sortOption === 'dateDesc') {
                    return new Date(b.date_listed) - new Date(a.date_listed);
                }
                return 0;
            });
        },

        paginatedProperties() {
            return this.properties;
        },

        totalPages() {
            return this.meta.last_page || 1; // Provide a default value of 1 if meta.last_page is undefined
        },
    },

    methods: {
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchProperties();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchProperties();
            }
        },

        fetchProperties() {
            const url = this.province
                ? `${API_BASE_URL}/api/properties/${encodeURIComponent(this.province)}`
                : `${API_BASE_URL}/api/properties`;

            const params = {
                page: this.currentPage,
                per_page: this.perPage,
                'filter[title]': `*${this.searchTitle}*`,
                'filter[street]': `*${this.searchLocation}*`,
                'filter[for_sale]': 1,
                'filter[sold]': 0,
                sort: this.getSortOption(),
            };

            axios.get(url, { params })
                .then(response => {
                    this.properties = response.data.data;
                    this.meta = response.data.meta;

                    if (this.properties.length === 0 && this.province) {
                        this.$inertia.visit('/404', { replace: true });
                    }
                })
                .catch(error => {
                    console.error('Error fetching properties:', error);
                });
        },

        getSortOption() {
            switch (this.sortOption) {
                case 'priceAsc':
                    return 'price';
                case 'priceDesc':
                    return '-price';
                case 'dateAsc':
                    return 'date_listed';
                case 'dateDesc':
                    return '-date_listed';
                default:
                    return '';
            }
        },

        formatDate(date) {
            return format(new Date(date), 'MMMM d, yyyy HH:mm:ss');
        },
        formatPrice(price, currencySymbol) {
            return `${currencySymbol}${Number(price).toLocaleString('en-US')}`;
        },
    },

    created() {
        this.province = this.$page.props.province || '';
        this.fetchProperties();
    },

    watch: {
        currentPage() {
            this.fetchProperties();
        },

        searchTitle() {
            this.currentPage = 1;
            this.fetchProperties();
        },

        searchLocation() {
            this.currentPage = 1;
            this.fetchProperties();
        },

        sortOption() {
            this.fetchProperties();
        },

        '$page.props.province': {
            handler(newProvince) {
                this.province = newProvince || '';
                this.currentPage = 1;
                this.fetchProperties();
            },
            immediate: true,
        },
    },
};
</script>
