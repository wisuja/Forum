<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
        <textarea
          name="body"
          id="body"
          class="form-control"
          rows="5"
          placeholder="Have something to say?"
          v-model="body"
          required
        ></textarea>
      </div>
      <button type="submit" class="btn btn-primary" @click="addReply">
        Post
      </button>
    </div>
    <div v-else class="text-center">
      <p>
        You need to <a href="/login">sign in</a> to participate in the thread.
      </p>
    </div>
  </div>
</template>

<script>
import 'at.js';
import 'jquery.caret';

export default {
  data() {
    return {
      body: "",
    };
  },
  computed: {
    signedIn() {
      return window.App.signedIn;
    },
  },
  mounted() {
    $('#body').atwho({
      at: '@',
      delay: 750,
      callbacks: {
        remoteFilter: function(query, callback) {
          $.getJSON('/api/users', {name: query}, function(usernames) {
            callback(usernames);
          })
        }
      }
    })
  },
  methods: {
    addReply() {
      axios
        .post(location.pathname + "/replies", { body: this.body })
        .then(({ data }) => {
          this.body = "";

          flash("Your reply has been posted.");

          this.$emit("created", data);
        })
        .catch(({ response: { data } }) => {
          flash(data, "danger");
        });
    },
  },
};
</script>