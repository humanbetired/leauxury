function generateUUID() {
  return crypto.randomUUID()
}

export function useIdentifier() {
  let identifier = localStorage.getItem('device_id')

  if (!identifier) {
    identifier = generateUUID()
    localStorage.setItem('device_id', identifier)
  }

  return identifier
}