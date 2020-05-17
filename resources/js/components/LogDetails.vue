<template>
  <div v-if="propLog">
    <div class="flex justify-between bg-gray-100 text-sm m-2 p-2">
      <div><b>When:</b> {{ relativeTime }}</div>
      <div>
        <b>Request time:</b> {{ propLog.total_time }} ms
      </div>
      <div>
        <div>
          <b>Status:</b>
          <span>{{ propLog.reason_phrase }}</span>
          <status-pill :prop-status-code="propLog.status_code"></status-pill>
        </div>
      </div>
    </div>

    <div v-if="propLog.query_params" class="my-2 p-2">
      <div class="text-2xl pb-2">Query parameters</div>
      <table class="text-sm table-auto w-full bg-gray-100 border-2 border-gray-200">
        <tr v-for="(params, key) in propLog.query_params" :key="key" class="border-b">
          <td class="p-2 font-semibold w-1/2">{{ key }}</td>
          <td class="w-1/2">{{ params }}</td>
        </tr>
      </table>
    </div>

    <div class="flex">
      <div
        class="w-1/2 text-center p-3 border-b-2 text-gray-500 cursor-pointer mx-1"
        :class="{'border-blue-500 text-blue-500': showResponse}"
        @click="viewResponseData">
        Response
      </div>
      <div
        class="w-1/2 text-center p-3 border-b-2 text-gray-500 cursor-pointer mx-1"
        :class="{'border-blue-500 text-blue-500': showRequest}"
        @click="viewRequestData">
        Request
      </div>
    </div>
    <div v-if="showResponse" class="p-2">
      <div class="my-2">
        <div class="text-2xl pb-2">Headers</div>
        <table class="text-sm bg-gray-100 border-2 border-gray-200 w-full">
          <tr v-for="(header, key) in propLog.response_headers" :key="key" class="border-b">
            <td class="p-2 font-semibold w-1/2">{{ key }}</td>
            <td class="w-1/2">{{ Array.isArray(header) ? header[0] : header }}</td>
          </tr>
        </table>
      </div>

      <div class="my-4">
        <div class="text-2xl pb-2">Body</div>
        <pre class="bg-gray-100 border-2 border-gray-200 overflow-auto p-2 text-xs">{{ propLog.response_body }}</pre>
      </div>
    </div>
    <div v-if="showRequest" class="p-2">
      <div class="my-2">
        <div class="text-2xl pb-2">Headers</div>
        <table class="text-sm bg-gray-100 border-2 border-gray-200 w-full">
          <tr v-for="(header, key) in propLog.request_headers" :key="key" class="border-b">
            <td class="p-2 font-semibold w-1/2">{{ key }}</td>
            <td class="w-1/2">{{ Array.isArray(header) ? header[0] : header }}</td>
          </tr>
        </table>
      </div>

      <div v-if="propLog.request_body" class="my-4">
        <div class="text-2xl pb-2">Body</div>
        <pre class="bg-gray-100 border-2 border-gray-200 overflow-auto p-2 text-xs">{{ propLog.request_body }}</pre>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import StatusPill from './StatusPill.vue'

export default {
  props: {
    propLog: {
      type: Object
    }
  },

  data() {
    return {
      showRequest: false,
      showResponse: true
    }
  },

  computed: {
    relativeTime() {
      return moment.utc(this.propLog.created_at).local().fromNow()
    }
  },

  methods: {
    viewRequestData() {
      this.showRequest = true
      this.showResponse = false
    },
    viewResponseData() {
      this.showRequest = false
      this.showResponse = true
    }
  },

  components: {
    StatusPill,
  }
}
</script>
