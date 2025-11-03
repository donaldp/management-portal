<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref, nextTick } from "vue";

const props = defineProps({
    people: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    languages: {
        type: Array,
        default: () => [],
    },
    interests: {
        type: Array,
        default: () => [],
    },
});

const name = ref(props.filters.name || "");
const languageId = ref(props.filters.language_id || "");
const interestId = ref(props.filters.interest_id || "");
const archiveStatus = ref(props.filters.archived || "active");

function applyFilters() {
    const query = new URLSearchParams({
        name: name.value || "",
        language_id: languageId.value || "",
        interest_id: interestId.value || "",
        archived: archiveStatus.value || "",
    }).toString();

    window.location.href = `/dashboard?${query}`;
}

const confirmingArchive = ref(false);
const selectedPerson = ref(null);
const form = useForm({});

function confirmArchive(person) {
    selectedPerson.value = person;
    confirmingArchive.value = true;
}

function archivePerson() {
    if (!selectedPerson.value) return;

    form.patch(`/people/${selectedPerson.value.id}/${selectedPerson.value.archived_at ? 'unarchive' : 'archive'}`, {
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
}

function closeModal() {
    confirmingArchive.value = false;
    selectedPerson.value = null;
    form.clearErrors();
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div
                    class="bg-white p-6 shadow sm:rounded-lg flex flex-wrap gap-4 items-end"
                >
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Name</label
                        >
                        <input
                            type="text"
                            v-model="name"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Language</label
                        >
                        <select
                            v-model="languageId"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">All</option>
                            <option
                                v-for="lang in languages"
                                :key="lang.id"
                                :value="lang.id"
                            >
                                {{ lang.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Interest</label
                        >
                        <select
                            v-model="interestId"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">All</option>
                            <option
                                v-for="i in interests"
                                :key="i.id"
                                :value="i.id"
                            >
                                {{ i.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Status</label
                        >
                        <select
                            v-model="archiveStatus"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="active">Active</option>
                            <option value="archived">Archived</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <PrimaryButton @click="applyFilters">Filter</PrimaryButton>
                    <Link :href="route('people.create')">
                        <PrimaryButton>Create</PrimaryButton>
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        First Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Last Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Mobile
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        DOB
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Language
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Interests
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="person in props.people?.data || []"
                                    :key="person?.id"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.first_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.email_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.mobile_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.date_of_birth }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ person?.language?.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded mr-1"
                                        >
                                            +
                                            {{ person?.interests?.length || 0 }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    >
                                        <Link
                                            v-if="person"
                                            :href="`/people/${person.id}    `"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >Edit</Link
                                        >
                                        <DangerButton
                                            @click="confirmArchive(person)"
                                        >
                                            {{
                                                person.archived_at
                                                    ? "Unarchive"
                                                    : "Archive"
                                            }}
                                        </DangerButton>
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        (props.people?.data || []).length === 0
                                    "
                                >
                                    <td
                                        colspan="9"
                                        class="px-6 py-4 text-center text-gray-500"
                                    >
                                        No people found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 flex justify-end space-x-2 p-4">
                        <button
                            :disabled="!props.people?.prev_page_url"
                            @click="$inertia.visit(props.people.prev_page_url)"
                            class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50"
                        >
                            Previous
                        </button>
                        <button
                            :disabled="!props.people?.next_page_url"
                            @click="$inertia.visit(props.people.next_page_url)"
                            class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <Modal :show="confirmingArchive" @close="closeModal">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Are you sure you want to
                            {{
                                selectedPerson?.archived_at
                                    ? "unarchive"
                                    : "archive"
                            }}
                            this person?
                        </h2>

                        <div class="mt-6 flex justify-end">
                            <PrimaryButton @click="closeModal" class="mr-2"
                                >Cancel</PrimaryButton
                            >
                            <DangerButton @click="archivePerson">
                                {{
                                    selectedPerson?.archived_at
                                        ? "Unarchive"
                                        : "Archive"
                                }}
                            </DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
