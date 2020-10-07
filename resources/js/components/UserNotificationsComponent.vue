<template>
  <li class="nav-item dropdown" v-if="notifications.length">
    <a href="" class="nav-link" data-toggle="dropdown">
      <i class="fas fa-bell"></i>
    </a>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications" :key="notification.id">
        <a
          :href="notification.data.link"
          class="dropdown-item"
          v-text="notification.data.message"
          @click="markAsRead(notification)"
        ></a>
      </li>
    </ul>
  </li>
</template>
  
<script>
export default {
  data() {
    return {
      notifications: false,
    };
  },

  created() {
    axios
      .get(`/profiles/${window.App.user.name}/notifications`)
      .then((res) => (this.notifications = res.data));
  },

  methods: {
    markAsRead(notification) {
      axios.delete(
        `/profiles/${window.App.user.name}/notifications/${notification.id}`
      );
    },
  },
};
</script>