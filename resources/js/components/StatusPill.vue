<template>
  <span
    class="py-1 px-2 rounded-lg text-sm"
    :class="statusColor(propStatusCode)">{{ propStatusCode }}</span>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    propStatusCode: {
      type: String
    }
  },

  methods: {
    statusColor(status) {
      let color = ''

      if (status >= 100 && status < 200) {
          color = 'bg-gray-300'
      } else if (status >= 200 && status < 300) {
          color = 'bg-green-200'
      } else if (status >= 300 && status < 400) {
          color = 'bg-blue-200'
      } else if (status >= 400 && status < 500) {
          color = 'bg-orange-200'
      } else if (status >= 500) {
          color = 'bg-red-200'
      }

      return color
    }
  }
}
</script>
