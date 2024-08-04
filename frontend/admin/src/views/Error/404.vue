<template>
  <div class="container">
    <div>
      <div style="font-size: 30vh">404</div>

      <div class="text-h2" style="opacity:.4">
        {{ $t('views.error.404.label.main')}}
      </div>

      <router-link to="/">
        <Button :label="$t('views.error.404.button.go-home')" severity="secondary" />
      </router-link>

    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: '404'
})
</script>
