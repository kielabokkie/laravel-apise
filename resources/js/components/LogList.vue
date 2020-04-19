<template>
  <ul v-if="selectedItem">
    <li v-for="log in logs"
      class="flex justify-between p-2 m-2"
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
</template>

<script>
import axios from 'axios'
import StatusPill from './StatusPill.vue'

export default {
  props: {
    value: {
      type: Object
    }
  },

  data() {
    return {
      logs: [],
      selectedItem: this.value
    }
  },

  created () {
    this.fetch()
  },

  methods: {
    selectItem(log) {
      this.selectedItem = log
      this.$emit("input", this.selectedItem)
    },
    fetch() {
      axios.get('/apise/api/logs')
        .then((response) => {
          this.logs = response.data
        })
        .catch((error) => {
          this.logs = [
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
        })
        .then(() => {
          if (this.selectedItem === null) {
            this.selectItem(this.logs[0])
          }
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
