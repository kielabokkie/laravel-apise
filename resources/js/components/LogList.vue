<template>
  <div>
    <ul v-if="selectedItem">
      <li v-for="log in logs"
        class="flex justify-between p-2 m-2 shadow-sm"
        :class="[selectedItem.id === log.id ? 'bg-gray-200' : 'bg-white']"
        :key="log.id"
        @click="selectItem(log)"
      >
        <div class="overflow-auto truncate mr-2">
          <span class="border-l-4 px-1" :class="methodColor(log.method)">{{ log.method }}</span> <span :title="log.uri">{{ log.uri }}</span>
        </div>
        <div class="flex-none">
          <span v-if="log.tag" class="py-1 px-2 rounded-lg text-sm bg-gray-300">{{ log.tag }}</span>
          <status-pill :prop-status-code="log.status_code"></status-pill>
        </div>
      </li>
    </ul>
    <div
      v-if="canLoadMore"
      class="text-center m-2 py-1 text-xs uppercase border-gray-400 border cursor-pointer hover:bg-gray-300"
      @click="loadMore">
      Load more
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import StatusPill from './StatusPill.vue'

export default {
  props: {
    value: {
      type: Object
    },
    propLogs: {
      type: Array
    },
    propTotalLogs: {
      type: Number
    }
  },

  data() {
    return {
      logs: [],
      selectedItem: this.value,
      canLoadMore: false
    }
  },

  created () {
    this.logs = this.propLogs

    if (this.selectedItem === null) {
      this.selectItem(this.logs[0])
    }

    if (this.propTotalLogs > this.logs.length) {
      this.canLoadMore = true
    }
  },

  methods: {
    selectItem(log) {
      this.selectedItem = log
      this.$emit("input", this.selectedItem)
    },
    loadMore() {
      let lastIndex = this.logs.length - 1
      let bottomLog = this.logs[lastIndex]

      axios.get('/apise/api/logs/' + bottomLog.id)
        .then((response) => {
          this.logs = this.logs.concat(response.data.logs)

          if (response.data.total === this.logs.length) {
            this.canLoadMore = false
          }
        })
        .catch((error) => {
          //
        })
    },
    methodColor(method) {
      let color = ''

      switch (method) {
        case 'GET':
          color = 'border-green-500'
          break
        case 'POST':
          color = 'border-blue-500'
          break
        case 'DELETE':
          color = 'border-red-500'
          break
        case 'PUT':
        case 'PATCH':
          color = 'border-orange-400'
          break
        default:
          color = 'border-gray-600'
          break
      }

      return color
    }
  },

  components: {
    StatusPill,
  }
}
</script>
