<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from "@inertiajs/vue3";
import { FilterMatchMode } from '@primevue/core/api';
import { useConfirm } from "primevue/useconfirm";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    books: Object,
});

const can = usePage().props.auth.permissions;

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    title: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.first_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.last_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    available: { value: null, matchMode: FilterMatchMode.EQUALS },
});

// Randomize books to feature different ones each page load
const featured = ref([])
onMounted(() => {
    randomize();
});

const randomize = () => {
    featured.value = props.books.sort((a, b) => 0.5 - Math.random()).slice(0, 5);
}

const confirm = useConfirm();

const checkoutBook = (bookId) => {
    confirm.require({
        message: 'Are you sure you want to check out this book?',
        header: 'Confirmation',
        rejectProps: {
            label: 'No thanks',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes it looks great!'
        },
        accept: () => {
            router.put(route("book.checkout", bookId));
        },
        reject: () => {
            //
        }
    });
};

const returnBook = (bookId) => {
    confirm.require({
        message: 'Are you sure you want to return this book?',
        header: 'Confirmation',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes Mark As Returned'
        },
        accept: () => {
            router.put(route("book.return", bookId));
        },
        reject: () => {
            //
        }
    });
};

</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    Welcome {{ $page.props.auth.user.name }}!
                </h2>
                <div v-if="$page.props.flash.success" class="alert alert-success">
                    <Message severity="success">{{ $page.props.flash.success }}</Message>
                </div>
                <div v-if="$page.props.flash.error" class="alert alert-danger">
                    <Message severity="error">{{ $page.props.flash.error }}</Message>
                </div>
                <Button @click="randomize" label="Surprise me!" size="small" />
            </div>
        </template>
        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Book Catalog</h2>
                        <p>Enjoy this selection of featured books or search for any title or author.</p>
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
                            <Column header="Actions" style="width: 12%">
                                <template #body="{ data }">
                                    <Button v-if="data.available && can.checkout_book"
                                        @click="checkoutBook(data.id)"  label="Check Out" size="small" type="button" />

                                    <Button v-if="data.availability_date && can.return_book"
                                        @click="returnBook(data.id)" label="Return Book" size="small" type="button" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
