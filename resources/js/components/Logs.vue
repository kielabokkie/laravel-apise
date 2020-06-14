<template>
  <div>
    <template v-if="logs && logs.length > 0">
      <div class="flex my-8 bg-white border-2 border-gray-200 shadow-lg">
        <div class="w-2/5 border-r-2 border-gray-200 bg-gray-100">
          <log-list
            v-model="selectedItem"
            :prop-logs="logs"
            :prop-total-logs="logsTotal">
          </log-list>
        </div>
        <div class="w-3/5 overflow-auto p-2">
          <log-details :prop-log="selectedItem"></log-details>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="text-center text-gray-400 text-xl w-full m-12 pulse">
        <div class="lds-ellipsis">
          <div></div><div></div><div></div><div></div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
import LogList from './LogList'
import LogDetails from './LogDetails'

export default {
  name: 'app',

  data() {
    return {
      logs: [],
      logsTotal: 0,
      selectedItem: null,
      polling: null
    }
  },

  created () {
    this.fetch()
  },

  methods: {
    fetch() {
      axios.get('/apise/api/logs')
        .then((response) => {
          this.logs = response.data.logs
          this.logsTotal = response.data.total
        })
        .catch((error) => {
          //
        })
        .then(() => {
          this.fetchLatest()
        })
    },
    fetchLatest() {
      this.polling = setInterval(() => {
        let topLog = this.logs[0]

        axios.get('/apise/api/logs/latest/' + topLog.id)
          .then((response) => {
            let newRecords = response.data

            // Add new records at the beginning of the logs array
            if (newRecords.length > 0) {
              this.logs.unshift(...newRecords)
            }
          })
          .catch((error) => {
            //
          })
      }, 2500)
    }
  },

  beforeDestroy () {
    clearInterval(this.polling)
  },

  components: {
    LogList,
    LogDetails
  }
}
</script>

<style scoped>
.lds-ellipsis {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ellipsis div {
  position: absolute;
  top: 33px;
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background: #cbd5e0;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
  left: 8px;
  animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
  left: 8px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
  left: 32px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
  left: 56px;
  animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes lds-ellipsis3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes lds-ellipsis2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}
</style>
