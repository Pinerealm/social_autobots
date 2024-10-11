<script>
import axios from 'axios';

export default {
    data() {
        return {
            autobotCount: 0
        };
    },
    mounted() {
        this.fetchAutobotCount();

        // Poll the server every 10 seconds to update the count
        setInterval(() => {
            this.fetchAutobotCount();
        }, 10000);
    },
    methods: {
        fetchAutobotCount() {
            axios.get('/api/autobot-count')
                .then(response => {
                    this.autobotCount = response.data.count;
                })
                .catch(error => {
                    console.error("There was an error fetching the Autobots count:", error);
                });
        }
    }
}
</script>

<template>
    <div>
        <h2>Real-Time Autobots Count: {{ autobotCount }}</h2>
    </div>
</template>
