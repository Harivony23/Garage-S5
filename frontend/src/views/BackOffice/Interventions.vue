<template>
  <div class="flex flex-col h-screen bg-base-200">
    <div class="navbar bg-base-100 shadow-md">
      <div class="flex-1 px-4">
        <a class="text-xl font-bold text-primary">Garage Backoffice</a>
      </div>
      <div class="flex-none gap-2 px-4">
        <router-link to="/admin" class="btn btn-ghost btn-sm">Tableau de bord</router-link>
        <button @click="logout" class="btn btn-outline btn-error btn-sm">Déconnexion</button>
      </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside class="w-64 bg-base-100 border-r border-base-300 hidden md:block">
        <ul class="menu p-4 w-full">
          <li><router-link to="/">Accueil Front</router-link></li>
          <li><router-link to="/admin">Tableau de bord</router-link></li>
          <li><router-link to="/admin/interventions" class="active">Interventions</router-link></li>
        </ul>
      </aside>

      <!-- Content -->
      <main class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold">Gestion des Interventions</h1>
          <button @click="openModal()" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nouvelle Intervention
          </button>
        </div>

        <div class="bg-base-100 rounded-xl shadow-xl overflow-hidden">
          <table class="table table-zebra w-full text-center">
            <thead>
              <tr class="bg-base-200">
                <th>Nom</th>
                <th>Prix (€)</th>
                <th>Durée (minutes)</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="intervention in interventions" :key="intervention.id" class="hover">
                <td class="font-semibold">{{ intervention.nom }}</td>
                <td>{{ intervention.prix }}</td>
                <td>{{ intervention.duree_minutes }}</td>
                <td class="flex justify-center gap-2">
                  <button @click="openModal(intervention)" class="btn btn-square btn-outline btn-sm btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                  </button>
                  <button @click="deleteIntervention(intervention.id)" class="btn btn-square btn-outline btn-sm btn-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-if="interventions.length === 0" class="py-10 text-center opacity-50 italic">
            Aucune intervention enregistrée
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Form -->
    <dialog id="intervention_modal" class="modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ editingId ? 'Modifier' : 'Nouvelle' }} Intervention</h3>
        <div class="form-control w-full mb-4">
          <label class="label"><span class="label-text font-semibold">Nom</span></label>
          <input v-model="form.nom" type="text" placeholder="ex: Vidange" class="input input-bordered w-full" />
        </div>
        <div class="flex gap-4">
          <div class="form-control w-1/2">
            <label class="label"><span class="label-text font-semibold">Prix (€)</span></label>
            <input v-model="form.prix" type="number" step="0.01" placeholder="0.00" class="input input-bordered w-full" />
          </div>
          <div class="form-control w-1/2">
            <label class="label"><span class="label-text font-semibold">Durée (minutes)</span></label>
            <input v-model="form.duree_minutes" type="number" placeholder="60" class="input input-bordered w-full" />
          </div>
        </div>
        <div class="modal-action">
          <button class="btn btn-ghost" @click="closeModal">Annuler</button>
          <button class="btn btn-primary" @click="saveIntervention">Enregistrer</button>
        </div>
      </div>
    </dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const interventions = ref([])
const BACKEND_URL = 'http://localhost:9000/api'

const form = ref({ nom: '', prix: '', duree_minutes: '' })
const editingId = ref(null)

const fetchInterventions = async () => {
  try {
    const response = await axios.get(`${BACKEND_URL}/backoffice/interventions`)
    interventions.value = response.data
  } catch (error) {
    console.error('Error fetching interventions:', error)
  }
}

const openModal = (intervention = null) => {
  if (intervention) {
    editingId.value = intervention.id
    form.value = { ...intervention }
  } else {
    editingId.value = null
    form.value = { nom: '', prix: '', duree_minutes: '' }
  }
  document.getElementById('intervention_modal').showModal()
}

const closeModal = () => {
  document.getElementById('intervention_modal').close()
}

const saveIntervention = async () => {
  try {
    if (editingId.value) {
      await axios.put(`${BACKEND_URL}/backoffice/interventions/${editingId.value}`, form.value)
    } else {
      await axios.post(`${BACKEND_URL}/backoffice/interventions`, form.value)
    }
    fetchInterventions()
    closeModal()
  } catch (error) {
    console.error('Error saving intervention:', error)
  }
}

const deleteIntervention = async (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')) {
    try {
      await axios.delete(`${BACKEND_URL}/backoffice/interventions/${id}`)
      fetchInterventions()
    } catch (error) {
      console.error('Error deleting intervention:', error)
    }
  }
}

const logout = () => {
  localStorage.removeItem('isLoggedIn')
  router.push('/login')
}

onMounted(fetchInterventions)
</script>
