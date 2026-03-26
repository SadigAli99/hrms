tailwind.config = {
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        display: ['Syne', 'sans-serif'],
        body: ['DM Sans', 'sans-serif']
      },
      colors: {
        brand: {
          50: '#eef6ff',
          100: '#d9ebff',
          200: '#bbdaff',
          300: '#8ec2ff',
          400: '#599eff',
          500: '#3178f6',
          600: '#1a5aeb',
          700: '#1646d8',
          800: '#183aaf',
          900: '#1a368a'
        },
        surface: {
          900: '#0a0d14',
          800: '#0f1420',
          700: '#151c2e',
          600: '#1c2640',
          500: '#243050',
          400: '#2e3d60',
          300: '#3a4d76'
        },
        accent: '#22d3ee',
        success: '#10b981',
        warn: '#f59e0b',
        danger: '#f43f5e',
        gold: '#f5a623'
      }
    }
  }
};
