<template>
  <div class="flex flex-col h-screen bg-base-200">
    <div class="navbar bg-base-100 shadow-md">
      <div class="flex-1 px-4">
        <a class="text-xl font-bold text-primary">Garage Backoffice</a>
      </div>
      <div class="flex-none gap-2 px-4">
        <button @click="handleFirebaseSync" class="btn btn-ghost btn-sm text-secondary gap-2">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
          </svg>
          Sync Firebase
        </button>
        <button @click="logout" class="btn btn-outline btn-error btn-sm">Déconnexion</button>
      </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside class="w-64 bg-base-100 border-r border-base-300 hidden md:block">
        <ul class="menu p-4 w-full">
          <li><router-link to="/">Accueil Front</router-link></li>
          <li><router-link to="/admin" class="active">Tableau de bord</router-link></li>
          <li><router-link to="/admin/interventions">Interventions</router-link></li>
        </ul>
      </aside>

      <!-- Content -->
      <main class="flex-1 overflow-y-auto p-6">
        <h1 class="text-3xl font-bold mb-8">Tableau Statistiques</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div class="stats shadow bg-gradient-to-br from-primary to-primary-focus text-primary-content">
            <div class="stat">
              <div class="stat-title text-primary-content opacity-70">Montant total</div>
              <div class="stat-value">{{ stats.total_montant || 0 }} €</div>
              <div class="stat-desc">Interventions réalisées</div>
            </div>
          </div>
          
          <div class="stats shadow bg-gradient-to-br from-secondary to-secondary-focus text-secondary-content">
            <div class="stat">
              <div class="stat-title text-secondary-content opacity-70">Nombre de clients</div>
              <div class="stat-value">{{ stats.nombre_clients || 0 }}</div>
              <div class="stat-desc">Au total</div>
            </div>
          </div>

          <div class="stats shadow bg-base-100">
            <div class="stat border-l-4 border-accent">
              <div class="stat-title">Capacité de Garage</div>
              <div class="stat-value text-accent">2</div>
              <div class="stat-desc text-xs font-semibold">Postes de travail disponibles</div>
            </div>
          </div>
        </div>

        <div class="bg-base-100 rounded-xl shadow-xl p-6">
          <h2 class="text-xl font-bold mb-4">Vue d'ensemble - Réparations en cours</h2>
          <div class="overflow-x-auto">
             <div v-if="isLoadingRepairs" class="flex justify-center py-10">
                <span class="loading loading-dots loading-lg text-primary"></span>
             </div>
             <table v-else class="table table-zebra w-full">
                <thead>
                  <tr>
                    <th>Voiture</th>
                    <th>Client</th>
                    <th>Statut</th>
                    <th>Slot</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="repair in reparations" :key="repair.id">
                    <td>{{ repair.voiture.marque.nom }} {{ repair.voiture.modele }} ({{ repair.voiture.immatriculation }})</td>
                    <td>{{ repair.voiture.client.nom }} {{ repair.voiture.client.prenom }}</td>
                    <td><div class="badge badge-outline" :class="{'badge-primary': repair.statut === 'en_cours', 'badge-secondary': repair.statut === 'en_attente'}">{{ repair.statut }}</div></td>
                    <td><div class="badge badge-neutral shadow-sm">Slot {{ repair.slot }}</div></td>
                  </tr>
                </tbody>
             </table>
             <div v-if="!isLoadingRepairs && reparations.length === 0" class="alert alert-info shadow-sm py-3 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Aucune réparation en cours ou en attente.
             </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Firebase -->
    <dialog id="firebase_modal" class="modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg text-warning">Firebase Sync</h3>
        <p class="py-4">La synchronisation Firebase n'est pas encore implémentée sur le backend.</p>
        <div class="modal-action">
          <form method="dialog">
            <button class="btn">Fermer</button>
          </form>
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
const stats = ref({})
const reparations = ref([])
const isLoading = ref(true)
const isLoadingRepairs = ref(true)
const BACKEND_URL = 'http://localhost:9000/api'

const fetchStats = async () => {
  isLoading.value = true
  try {
    const response = await axios.get(`${BACKEND_URL}/backoffice/statistiques`)
    stats.value = response.data
  } catch (error) {
    console.error('Error fetching stats:', error)
  } finally {
    isLoading.value = false
  }
}

const fetchRepairs = async () => {
  isLoadingRepairs.value = true
  try {
    const response = await axios.get(`${BACKEND_URL}/frontoffice/reparations`)
    reparations.value = response.data
  } catch (error) {
    console.error('Error fetching repairs:', error)
  } finally {
    isLoadingRepairs.value = false
  }
}

const handleFirebaseSync = () => {
  document.getElementById('firebase_modal').showModal()
}

const logout = () => {
  localStorage.removeItem('isLoggedIn')
  router.push('/login')
}

onMounted(() => {
  fetchStats()
  fetchRepairs()
})
</script>
