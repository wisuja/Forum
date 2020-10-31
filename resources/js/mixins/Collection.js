export default {
    data() {
        return {
            items: []
        };
    },

    methods: {
        add(item) {
            this.items.push(item);

            this.$emit("created", item);
        },
        remove(index) {
            this.items.splice(index, 1);

            this.$emit("removed");
        }
    }
};
