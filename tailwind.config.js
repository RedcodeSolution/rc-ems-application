/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                redcode: {
                    primary: "#DC2626",
                    "primary-dark": "#991B1B",
                    "primary-light": "#FEE2E2",
                    accent: "#B91C1C",
                    dark: "#1F2937",
                    gray: "#6B7280",
                    light: "#F9FAFB",
                    white: "#FFFFFF",
                    blue: "#2563EB",
                    green: "#059669",
                    orange: "#D97706",
                    yellow: "#F59E0B",
                },
            },
            fontFamily: {
                inter: ["Inter", "sans-serif"],
            },
            animation: {
                "gradient-shift": "gradientShift 3s ease infinite",
                "bell-pulse": "bell-pulse 1.5s infinite",
            },
            keyframes: {
                gradientShift: {
                    "0%, 100%": { backgroundPosition: "0% 50%" },
                    "50%": { backgroundPosition: "100% 50%" },
                },
                "bell-pulse": {
                    "0%, 100%": { transform: "scale(1)", opacity: "1" },
                    "50%": { transform: "scale(1.3)", opacity: "0.7" },
                },
            },
        },
    },
    plugins: [],
};
