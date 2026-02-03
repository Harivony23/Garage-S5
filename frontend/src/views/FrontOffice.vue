<template>
  <div class="min-h-screen bg-base-200">
    <nav class="navbar bg-primary text-primary-content shadow-lg px-4 lg:px-12">
      <div class="flex-1">
        <a class="btn btn-ghost normal-case text-xl font-bold">Garage Prestige</a>
      </div>
      <div class="flex-none">
        <router-link to="/login" class="btn btn-ghost btn-circle">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
        </router-link>
      </div>
    </nav>

    <main class="container mx-auto p-4 lg:p-12">
      <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-4xl font-extrabold text-neutral">Réparations en cours</h1>
        <div class="stats shadow bg-base-100">
          <div class="stat">
            <div class="stat-title text-secondary">Capacité</div>
            <div class="stat-value text-secondary">2 / 2</div>
            <div class="stat-desc">Voitures en réparation</div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="reparation in ongoingReparations" :key="reparation.id" class="card bg-base-100 shadow-xl border-t-4 border-primary">
          <div class="card-body">
            <h2 class="card-title text-2xl">
              {{ reparation.voiture.marque }} - {{ reparation.voiture.modele }}
              <div class="badge badge-secondary">{{ reparation.voiture.immatriculation }}</div>
            </h2>
            <p class="text-neutral-500 italic">Client: {{ reparation.voiture.client.nom }}</p>
            
            <div class="divider my-2">Interventions</div>
            
            <ul class="space-y-2">
              <li v-for="item in reparation.interventions" :key="item.id" class="flex justify-between items-center">
                <span class="flex items-center gap-2">
                  <span v-if="item.statut === 'terminee'" class="badge badge-success badge-xs"></span>
                  <span v-else-if="item.statut === 'en_cours'" class="loading loading-spinner loading-xs text-primary"></span>
                  <span v-else class="badge badge-ghost badge-xs"></span>
                  {{ item.intervention.nom }}
                </span>
                <span class="text-xs opacity-70">{{ item.intervention.duree_minutes }} min</span>
              </li>
            </ul>

            <div class="card-actions justify-end mt-4">
              <div class="badge badge-outline p-4">{{ calculateTotal(reparation) }} €</div>
            </div>
          </div>
        </div>

        <div v-if="ongoingReparations.length === 0" class="col-span-full py-20 text-center">
          <div class="text-6xl mb-4"></div>
          <h3 class="text-2xl font-bold opacity-50">Aucune réparation en cours</h3>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const ongoingReparations = ref([])
const BACKEND_URL = 'http://localhost:9000/api' // Updated to port 9000 for Docker

const fetchReparations = async () => {
  try {
    const response = await axios.get(`${BACKEND_URL}/frontoffice/reparations`)
    ongoingReparations.value = response.data
  } catch (error) {
    console.error('Error fetching reparations:', error)
  }
}

const calculateTotal = (reparation) => {
  if (!reparation.interventions) return "0.00"
  return reparation.interventions.reduce((total, item) => total + parseFloat(item.intervention.prix), 0).toFixed(2)
}

onMounted(() => {
  fetchReparations()
  // Poll every 10 seconds
  setInterval(fetchReparations, 10000)
})
</script>
