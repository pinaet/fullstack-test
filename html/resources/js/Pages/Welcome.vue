<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Property Listings</h1>

        <div class="mb-4 flex flex-col sm:flex-row sm:items-center">
            <input type="text" v-model="searchTitle" placeholder="Search by title"
                class="w-full sm:w-auto sm:mr-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <input type="text" v-model="searchLocation" placeholder="Search by location"
                class="w-full sm:w-auto mt-2 sm:mt-0 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <select v-model="sortOption"
                class="w-full sm:w-auto mt-2 sm:mt-0 sm:ml-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sort by</option>
                <option value="priceAsc">Price (Low to High)</option>
                <option value="priceDesc">Price (High to Low)</option>
                <option value="dateAsc">Date Listed (Oldest First)</option>
                <option value="dateDesc">Date Listed (Newest First)</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="property in paginatedProperties" :key="property.id"
                class="bg-white rounded-lg shadow-md overflow-hidden">
                <img :src="property.photos.thumb" :alt="property.title" class="w-full h-48 object-cover" />
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ property.title }}</h3>
                    <p class="text-gray-600 mb-4">{{ property.description }}</p>
                    <p class="text-lg font-bold mb-2">{{ property.currency_symbol }}{{ property.price }}</p>
                    <p class="text-gray-500">{{ property.geo.province }}, {{ property.geo.country }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <button @click="previousPage" :disabled="currentPage === 1"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <span class="mx-4">Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            properties: [],
            searchTitle: '',
            searchLocation: '',
            sortOption: '',
            currentPage: 1,
            perPage: 25,
            province: '',
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

            const combinedProperties = titleMatchedProperties.concat(locationMatchedProperties);

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
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.sortedProperties.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.filteredProperties.length / this.perPage);
        },
    },
    methods: {
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        fetchProperties() {
            const url = this.province
                ? `http://localhost:8080/api/properties/${this.province}`
                : 'http://localhost:8080/api/properties';

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
    },
    created() {
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
        $route(to) {
            this.province = to.params.province || '';
            this.currentPage = 1;
            this.fetchProperties();
        },
    },
};
</script>
