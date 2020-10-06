<script>
import Replies from "../components/RepliesComponent.vue";
import SubscribeButton from "../components/SubscribeButtonComponent.vue";

export default {
  props: ["initialRepliesCount"],
  components: { Replies, SubscribeButton },
  data() {
    return {
      repliesCount: this.initialRepliesCount,
    };
  },
};
</script>