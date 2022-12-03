package main

import (
	"fmt"
	"io/ioutil"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	rucksacks := strings.Split(string(content), "\n")
	sum := 0

	for _, rucksack := range rucksacks {
		comp1 := rucksack[:len(rucksack)/2]
		comp2 := rucksack[len(rucksack)/2:]
		m := make(map[string]int)

		for _, i := range comp1 {
			for _, j := range comp2 {
				if j == i {
					m[string(i)] = letterIntToAlphaInt(i)
				}
			}
		}

		for _, v := range m {
			sum += v
		}
	}

	fmt.Printf("Part 1: %v\n", sum)

	sum = 0

	for i := 0; i < len(rucksacks); i += 3 {
		ruk1 := rucksacks[i]
		ruk2 := rucksacks[i+1]
		ruk3 := rucksacks[i+2]
		m := make(map[string]int)

		for _, i := range ruk1 {
			for _, j := range ruk2 {
				for _, k := range ruk3 {
					if i == j && i == k {
						m[string(i)] = letterIntToAlphaInt(i)
					}
				}
			}
		}

		for _, v := range m {
			sum += v
		}
	}

	fmt.Printf("Part 2: %v\n", sum)
}

func letterIntToAlphaInt(i int32) int {
	if i >= 65 && i <= 90 {
		return int(i%32 + 26)
	} else {
		return int(i % 32)
	}
}
