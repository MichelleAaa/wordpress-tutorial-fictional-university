import "../css/style.scss"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
// mobileMenu is for a mini nav icon for a mobile view.
import HeroSlider from "./modules/HeroSlider"
// This powers the slideshow
import GoogleMap from "./modules/GoogleMap"
import Search from "./modules/Search"
import MyNotes from "./modules/MyNotes"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const googleMap = new GoogleMap()
const search = new Search()
const myNotes = new MyNotes()
