<template>
  <div>
    <template v-if="logs.logs && logs.logs.length > 0">
      <div class="flex my-8 bg-white border-2 border-gray-200 shadow-lg">
        <div class="w-2/5 border-r-2 border-gray-200 bg-gray-100">
          <log-list v-model="selectedItem" :prop-logs="logs"></log-list>
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
          this.logs = response.data
        })
        .catch((error) => {
          this.logs = {
            total: 2,
            logs: [
              {
                'id': 2,
                "method": "GET",
                "uri": "https:\/\/httpbin.org\/get",
                "status_code": "200",
                "reason_phrase": "OK",
                "total_time": "800",
                "tag": "httpbin",
              },
              {
                "id": 1,
                "correlation_id": "f0924b44-d348-4d23-9192-c4b5265670ac",
                "method": "POST",
                "protocol_version": "1.1",
                "uri": "https://httpbin.org/post",
                "request_headers": {
                  "Content-Length": [
                    "36"
                  ],
                  "User-Agent": [
                    "GuzzleHttp/6.5.1 curl/7.68.0 PHP/7.1.33"
                  ],
                  "Content-Type": [
                    "application/json"
                  ],
                  "Host": [
                    "httpbin.org"
                  ],
                  "X-Api-LogID": [
                    "f0924b44-d348-4d23-9192-c4b5265670ac"
                  ]
                },
                "request_body": {
                  "test": "test",
                  "password": "pass123"
                },
                "status_code": "200",
                "reason_phrase": "OK",
                "total_time": "704",
                "response_headers": {
                  "Date": [
                    "Tue, 31 Mar 2020 04:59:38 GMT"
                  ],
                  "Content-Type": [
                    "application/json"
                  ],
                  "Content-Length": [
                    "547"
                  ],
                  "Connection": [
                    "keep-alive"
                  ],
                  "Server": [
                    "gunicorn/19.9.0"
                  ],
                  "Access-Control-Allow-Origin": [
                    "*"
                  ],
                  "Access-Control-Allow-Credentials": [
                    "true"
                  ]
                },
                "response_body": {
                  "args": {},
                  "data": "{\"test\":\"test\",\"password\":\"pass123\"}",
                  "files": {},
                  "form": {},
                  "headers": {
                    "Content-Length": "36",
                    "Content-Type": "application/json",
                    "Host": "httpbin.org",
                    "User-Agent": "GuzzleHttp/6.5.1 curl/7.68.0 PHP/7.1.33",
                    "X-Amzn-Trace-Id": "Root=1-5e82ce3a-cee691ec8c5cbd7e8df23f84",
                    "X-Api-Logid": "f0924b44-d348-4d23-9192-c4b5265670ac"
                  },
                  "json": {
                    "password": "pass123",
                    "test": "test"
                  },
                  "origin": "185.195.239.7",
                  "url": "https://httpbin.org/post"
                },
                "tag": "httpbin",
                "created_at": "2020-03-31 04:59:37"
              }
            ]
          }
        })
        .then(() => {
          this.fetchLatest()
        })
    },
    fetchLatest() {
      this.polling = setInterval(() => {
        let topLog = this.logs.logs[0]

        axios.get('/apise/api/logs/latest/' + topLog.id)
          .then((response) => {
            let newRecords = response.data

            // Add the new records at the beginning of the logs array
            this.logs.logs = newRecords.concat(this.logs.logs)
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
