<template>
  <div class="flex my-8 bg-white border-2 border-gray-200 shadow-lg">
    <div class="w-1/2 border-r-2 border-gray-200 bg-gray-100">
      <log-list v-model="selectedItem"></log-list>
    </div>
    <div class="w-1/2 overflow-auto p-2">
      <log-details :prop-log="selectedItem"></log-details>
    </div>
  </div>
</template>

<script>
import LogList from './components/LogList.vue'
import LogDetails from './components/LogDetails.vue'

export default {
  name: 'app',

  data() {
    return {
      selectedItem: null
    }
  },

  components: {
    LogList,
    LogDetails
  }
}
</script>
