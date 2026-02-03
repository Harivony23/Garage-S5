<template>
  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content flex-col lg:flex-row-reverse">
      <div class="text-center lg:text-left lg:ml-12">
        <h1 class="text-5xl font-bold text-primary">Backoffice Login</h1>
        <p class="py-6 text-neutral-500">Gérez votre garage avec style. Connectez-vous pour voir les statistiques et gérer les interventions.</p>
      </div>
      <div class="card shrink-0 w-full max-w-sm shadow-2xl bg-base-100 border-t-4 border-primary">
        <form @submit.prevent="handleLogin" class="card-body">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Email</span>
            </label>
            <input v-model="email" type="email" placeholder="admin@garage.com" class="input input-bordered focus:input-primary" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Mot de passe</span>
            </label>
            <div class="relative">
              <input 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                placeholder="••••••••" 
                class="input input-bordered focus:input-primary w-full pr-12" 
                required 
              />
              <button 
                type="button" 
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-primary hover:text-primary-focus transition-colors"
                @click="showPassword = !showPassword"
              >
                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057-5.064-7-9.542-7-4.477 0-8.268-2.943-9.542-7z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>
              </button>
            </div>
          </div>
          <div class="form-control mt-6">
            <button class="btn btn-primary text-white" :disabled="isLoading">
              <span v-if="isLoading" class="loading loading-spinner"></span>
              Connexion
            </button>
          </div>
          <div v-if="error" class="alert alert-error mt-4 py-2">
            <span class="text-sm font-medium">{{ error }}</span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const error = ref('')

const handleLogin = async () => {
  isLoading.value = true
  error.value = ''
  
  // Mock login for now
  setTimeout(() => {
    if (email.value === 'admin@garage.com' && password.value === 'admin123') {
      localStorage.setItem('isLoggedIn', 'true')
      router.push('/admin')
    } else {
      error.value = 'Email ou mot de passe incorrect'
    }
    isLoading.value = false
  }, 1000)
}
</script>
