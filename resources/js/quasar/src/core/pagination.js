import { ref } from 'vue';

export const pagination = {
	paginator(limit = 10, page = 1, sort_by = 'id', sort_direction = 'desc') {
		return ref({
			page: page,
			rowsPerPage: limit,
			rowsNumber: 0,
			sortBy: sort_by,
			descending: sort_direction === 'desc',

			reset() {
				this.page = page;
				this.rowsPerPage = limit;
				this.rowsNumber = 0;
				this.sortBy = sort_by;
				this.descending = sort_direction === 'desc';
				return this;
			},

			normalize() {
				return {
					limit: this.rowsPerPage,
					page: this.page,
					sort_by: this.sortBy,
					direction: this.descending ? 'desc' : 'asc',
				};
			},

			setTotal(total) {
				this.rowsNumber = total;
			},

			fromProps(props) {
				if (props.page !== undefined) {
					this.page = props.page;
				}

				if (props.rowsPerPage !== undefined) {
					this.rowsPerPage = props.rowsPerPage;
				}

				if (props.rowsNumber !== undefined) {
					this.rowsNumber = props.rowsNumber;
				}

				if (props.sortBy !== undefined) {
					this.sortBy = props.sortBy;
				}

				if (props.descending !== undefined) {
					this.descending = props.descending;
				}
			},
		});
	},
};
