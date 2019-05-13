export const environment = {
	production: true,
	apiServer: "https://threads-srv.knsh.red/api/v2",
	atanosHome: "https://atanos.ga/",
	support: {
		email: "support@threads.atanos.ga"
	},
	unauthorizedRoute: ["/user/login", "unauthorized"],
	tokenExpiredRoute: ["/user/login", "token-expired"]
};
