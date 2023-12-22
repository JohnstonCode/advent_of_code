package main

import (
	"fmt"
	"math"
	"os"
	"strconv"
	"strings"
)

type Map struct {
	destStart   int
	sourceStart int
	rangeLen    int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	part1 := part1(string(content))
	part2 := part2(string(content))

	fmt.Printf("Part1: %s\n", part1)
	fmt.Printf("Part2: %s\n", part2)
}

func part1(input string) string {
	parts := strings.Split(input, "\n\n")

	rawSeeds, rawSoil, rawFertilizer, rawWater, rawLight, rawTemp, rawHumidity, rawLocation := parts[0], parts[1], parts[2], parts[3], parts[4], parts[5], parts[6], parts[7]

	seeds := createSeedSlice(rawSeeds)
	soilMaps := createMap(rawSoil)
	fertilizerMaps := createMap(rawFertilizer)
	waterMaps := createMap(rawWater)
	lightMaps := createMap(rawLight)
	tempMaps := createMap(rawTemp)
	humidityMaps := createMap(rawHumidity)
	locationMaps := createMap(rawLocation)

	min := math.MaxInt

	for _, seed := range seeds {
		soil := getDest(seed, soilMaps)
		fertilizer := getDest(soil, fertilizerMaps)
		water := getDest(fertilizer, waterMaps)
		light := getDest(water, lightMaps)
		temp := getDest(light, tempMaps)
		humidity := getDest(temp, humidityMaps)
		location := getDest(humidity, locationMaps)

		min = int(math.Min(float64(min), float64(location)))
	}

	return strconv.Itoa(min)
}

func part2(input string) string {
	parts := strings.Split(input, "\n\n")

	rawSeeds, rawSoil, rawFertilizer, rawWater, rawLight, rawTemp, rawHumidity, rawLocation := parts[0], parts[1], parts[2], parts[3], parts[4], parts[5], parts[6], parts[7]

	seeds := createSeedSlice(rawSeeds)
	soilMaps := createMap(rawSoil)
	fertilizerMaps := createMap(rawFertilizer)
	waterMaps := createMap(rawWater)
	lightMaps := createMap(rawLight)
	tempMaps := createMap(rawTemp)
	humidityMaps := createMap(rawHumidity)
	locationMaps := createMap(rawLocation)

	for i := 0; i < math.MaxInt; i++ {
		humidity := getSource(i, locationMaps)
		temp := getSource(humidity, humidityMaps)
		light := getSource(temp, tempMaps)
		water := getSource(light, lightMaps)
		fertilizer := getSource(water, waterMaps)
		soil := getSource(fertilizer, fertilizerMaps)
		seed := getSource(soil, soilMaps)

		for j := 0; j < len(seeds); j += 2 {
			start := seeds[j]
			length := seeds[j+1]

			if seed >= start && seed <= start+length {
				return strconv.Itoa(i)
			}
		}
	}

	return "unable to find min location"
}

func strToInt(s string) int {
	i, _ := strconv.Atoi(s)

	return i
}

func createSeedSlice(s string) []int {
	parts := strings.Split(s, ": ")
	var seeds []int

	for _, num := range strings.Split(parts[1], " ") {
		seeds = append(seeds, strToInt(num))
	}

	return seeds
}

func createMap(s string) []Map {
	lines := strings.Split(s, "\n")
	var maps []Map

	for _, line := range lines[1:] {
		parts := strings.Split(line, " ")

		maps = append(maps, Map{
			destStart:   strToInt(parts[0]),
			sourceStart: strToInt(parts[1]),
			rangeLen:    strToInt(parts[2]),
		})
	}

	return maps
}

func getDest(n int, maps []Map) int {
	for _, m := range maps {
		if n >= m.sourceStart && n <= m.sourceStart+m.rangeLen {
			return n - m.sourceStart + m.destStart
		}
	}

	return n
}

func getSource(dest int, maps []Map) int {
	for _, m := range maps {
		if dest >= m.destStart && dest <= m.destStart+m.rangeLen {
			return dest - m.destStart + m.sourceStart
		}
	}

	return dest
}
