const WHATSAPP_NUMBER = '6285649836821'

export function getWhatsappLink(product) {
  const productUrl = `${window.location.origin}/products/${product.id}`
  const message = `Halo Leauxury Industrial, saya tertarik dengan produk ${product.title}.\n\nLink produk: ${productUrl}`
  return `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`
}