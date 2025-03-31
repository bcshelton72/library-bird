<script setup>
import { FilterMatchMode } from '@primevue/core/api';
import { ref } from 'vue';
import BookCheckoutButton from '@/Components/BookCheckoutButton.vue';
import BookDeleteButton from '@/Components/BookDeleteButton.vue';
import BookReturnButton from '@/Components/BookReturnButton.vue';
import BookUpdateButton from '@/Components/BookUpdateButton.vue';

const props = defineProps({
    books: Object,
    checkedOut: {
        type: Boolean,
        value: false,
    }
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
    <DataTable
        dataKey="id"
        v-model:filters="filters"
        :value="books" tableStyle="min-width: 50rem"
        paginator :rows="10" :rowsPerPageOptions="[10, 20, 50]"
        filterDisplay="menu"
        :globalFilterFields="['title', 'author.first_name', 'author.last_name']"
        removableSort
    >
        <template #header>
            <div class="flex justify-end">
                <IconField>
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText v-model="filters['global'].value" placeholder="Search all titles & authors" class="w-64" />
                </IconField>
            </div>
        </template>
        <template #empty> No books found. </template>
        <template #loading> Loading books! Please wait. </template>
        <Column header="Cover">
            <template #body="slotProps">
                <Link :href="route('book.show', slotProps.data.id)">
                    <img
                        :src="slotProps.data.cover_image
                            ? '/storage/' + slotProps.data.cover_image
                            : '/storage/cover_images/default/' + slotProps.data.id % 10 + '.png'"
                        :alt="slotProps.data.cover_image"
                        class="w-24
                        rounded"
                    />
                </Link>
            </template>
        </Column>
        <Column field="title" header="Title" sortable>
            <template #body="{ data }">
                <Link :href="route('book.show', data.id)">{{ data.title }}</Link>
            </template>
        </Column>
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
                {{ data.availability_date ? data.availability_date : 'Checked In' }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
                <Checkbox v-model="filterModel.value" :indeterminate="filterModel.value === null" binary @change="filterCallback()" />
            </template>
        </Column>
        <Column header="Actions" style="width: 15%">
            <template #body="{ data }">
                <BookCheckoutButton :available="data.available" :bookId="data.id" />
                <span v-if="checkedOut">
                    <BookReturnButton :availabilityDate="data.availability_date" :bookId="data.id" />
                </span>
                <span v-else>
                    <BookReturnButton v-if="route().current() == 'dashboard'" :availabilityDate="data.availability_date" :bookId="data.id" />
                    <BookUpdateButton v-if="route().current() == 'books.manage'" :bookId="data.id" class="mr-2" />
                    <BookDeleteButton v-if="route().current() == 'books.manage'" :bookId="data.id" />
                </span>
            </template>
        </Column>
    </DataTable>
</template>
