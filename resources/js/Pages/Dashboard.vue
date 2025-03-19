<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Rating from 'primevue/rating';

defineProps({
    books: Object,
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
                        <h1>Book Catalog</h1>
                        <DataTable :value="books" tableStyle="min-width: 50rem" removableSort>
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
                            <Column field="available" header="Availability" style="width: 12%" sortable>
                                <template #body="{ data, field }">
                                    {{ data.availability_date }}
                                    <Button v-if="!data.availability_date" label="Check Out" size="small" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
