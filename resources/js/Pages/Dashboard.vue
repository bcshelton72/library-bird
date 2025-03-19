<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import { FilterMatchMode } from '@primevue/core/api';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Rating from 'primevue/rating';

defineProps({
    books: Object,
});

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    title: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.first_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.last_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    available: { value: null, matchMode: FilterMatchMode.EQUALS },
});
</script>
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Welcome {{ $page.props.auth.user.name }}!
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <DataTable
                            v-model:filters="filters"
                            :value="books" tableStyle="min-width: 50rem"
                            paginator :rows="10" :rowsPerPageOptions="[10, 20, 50]"
                            filterDisplay="row"
                            :globalFilterFields="['title', 'author.first_name', 'author.last_name']"
                            removableSort
                        >
                            <template #header>
                                <div class="flex justify-end">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Search titles & authors" class="w-64" />
                                    </IconField>
                                </div>
                            </template>
                            <Column header="Cover">
                                <template #body="slotProps">
                                    <img
                                        :src="slotProps.data.cover_image
                                            ? 'storage/' + slotProps.data.cover_image
                                            : 'storage/profile_pictures/default.jpg'"
                                        :alt="slotProps.data.cover_image"
                                        class="w-24
                                        rounded"
                                    />
                                </template>
                            </Column>
                            <Column field="title" header="Title" sortable></Column>
                            <Column field="author.last_name" header="Author" sortable>
                                <template #body="{ data }">
                                    {{ data.author.first_name }} {{ data.author.last_name }}
                                </template>
                            </Column>
                            <Column field="description" header="Description"></Column>
                            <Column field="average_rating" header="Reviews">
                                <template #body="slotProps">
                                    <Rating :modelValue="slotProps.data.average_rating" readonly />
                                </template>
                            </Column>
                            <Column field="available" header="Availability" filterField="available" dataType="boolean" style="width: 12%" sortable>
                                <template #body="{ data }">
                                    {{ data.availability_date }}
                                    <Button v-if="!data.availability_date" label="Check Out" size="small" />
                                </template>
                                <template #filter="{ filterModel, filterCallback }">
                                    <Checkbox v-model="filterModel.value" :indeterminate="filterModel.value === null" binary @change="filterCallback()" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
